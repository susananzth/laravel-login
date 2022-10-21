<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }
        /*if (isset($input['photo'])) {
            $firebase_storage_path = 'Profile/';
            $localfolder           = public_path('profile-photos');
            if (!file_exists($localfolder)) {
                mkdir($localfolder, 0775, true);
            }
            //$filename = rand(0000, 9999).time().'.'.$input['photo']->extension();
            $file = $input['photo'];

            // generate a new filename. getClientOriginalExtension() for the file extension
            $filename = rand(0000, 9999).time() . '.' . $file->getClientOriginalExtension();
            // save to storage/app/photos as the new $filename
            Storage::disk('local')->put($localfolder. '/' . $filename, $file);


            //$path = $file->storeAs('firebase-temp-uploads', $filename);

            //$localfolder = Storage::url($filename);
            if ($localfolder) {
                $uploadedfile = fopen($localfolder. '/' . $filename, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                // will remove from local laravel folder
                unlink($localfolder. '/' . $filename);
            }
            $user->updateProfilePhoto($input['photo']);
        }

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
            $firebase_storage_path = 'Profile/';
            $uploadedfile = fopen(config('app.url').'/user/'.config('jetstream.profile_photo_disk', 'public') . '/' . $user->profile_photo_path, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $user->profile_photo_path]);
                // will remove from local laravel folder
                unlink(config('app.url').'/user/'.config('jetstream.profile_photo_disk', 'public'). '/' . $user->profile_photo_path);
        }*/

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'username' => $input['username'],
                'phone' => $input['phone'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
