<?php

class Core_UserController extends BaseController {

    public function getAccount()
    {
        LeftTab::
            addPanel()
                ->setTitle($this->activeUser->username)
                ->setBasePath('user')
                ->addTab('Profile',             'profile')
                ->addTab('Avatar',              'avatar')
                ->addTab('Preferences',         'preferences')
                ->addTab('Change Password',     'change-password')
                ->addTab('Change Theme',        'change-theme')
                ->buildPanel()
        ->make();
    }

    public function getView($userId = null)
    {
        if ($userId == null) {
            $this->redirect('/');
        }

        $user = User::find($userId);

        $this->setViewData('user', $user);
    }

    public function postProfile()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            // Create the object
            $user              = User::find($this->activeUser->id);
            $user->displayName = $input['displayName'];
            $user->firstName   = $input['firstName'];
            $user->lastName    = $input['lastName'];
            $user->email       = $input['email'];
            $user->location    = $input['location'];
            $user->url         = $input['url'];

            // Attempt to save the object
            $this->save($user);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success');
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getChangePassword() {}

    public function postChangePassword()
    {
        $input = e_array(Input::all());

        if ($input != null) {

            $user = User::find($this->activeUser->id);

            if ($input['newPassword'] != $input['newPasswordAgain']) {
                Ajax::addError('newPassword', 'Your new passwords did not match.');
            }

            if (!Hash::check($input['oldPassword'], $user->password)) {
                Ajax::addError('oldPassword', 'Please enter your current password.');
            }

            if (Ajax::errorCount() > 0) {
                return Ajax::sendResponse();
            }

            $user->password = $input['newPassword'];
            $this->save($user);

            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success');
            }

            return Ajax::sendResponse();
        }
    }

    public function getRules()
    {
        $messageTypes = Message_Type::orderByNameAsc()->get();
        $users        = User::orderBy('username', 'asc')->get();
        $inbox        = Message_Folder::find($this->activeUser->inbox);
        $folders      = Message_Folder::where('uniqueId', '!=', $this->activeUser->inbox)->where('user_id', $this->activeUser->id)->orderByNameAsc()->get();

        $this->setViewData('messageTypes', $messageTypes);
        $this->setViewData('users', $users);
        $this->setViewData('inbox', $inbox);
        $this->setViewData('folders', $folders);
    }

    public function getPreferences()
    {
        $preferences = User_Preference::where('hiddenFlag', 0)->orderByNameAsc()->get();

        $this->setViewData('preferences', $preferences);
    }

    public function postPreferences()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            foreach ($input['preference'] as $keyName => $value) {
                $preference = $this->activeUser->getPreferenceByKeyName($keyName);
                $this->activeUser->setPreferenceValue($preference->id, $value);
            }
        }

        Ajax::setStatus('success');

        return Ajax::sendResponse();
    }

    public function getAvatar()
    {
        $avatarPreference = $this->activeUser->getPreferenceByKeyName('AVATAR');
        $preferenceArray  = $this->activeUser->getPreferenceOptionsArray($avatarPreference->id);

        $this->setViewData('avatarPreference', $avatarPreference);
        $this->setViewData('preferenceArray', $preferenceArray);
    }

    public function postAvatar()
    {
        $this->skipView();

        if (Input::hasFile('avatar')) {
            CoreImage::addImage(public_path() .'/img/avatars/User', Input::file('avatar'), Str::studly($this->activeUser->username));
            $imageErrors = CoreImage::getErrors();

            if (count($imageErrors) > 0) {
                $this->addErrors($imageErrors);
            }

            return $this->redirect('/user/account#avatar', 'Avatar uploaded');
        } else {
            $input    = e_array(Input::all());
            $messages = array();

            // Set avatar preference
            $this->activeUser->setPreferenceValue($input['avatar_preference_id'], $input['avatar_preference']);

            $messages[] = 'Avatar preference updated.';
        }

        Ajax::setStatus('success');

        return Ajax::sendResponse();
    }

    public function getCropAvatar($tempImageId)
    {
        // set image path. verify it is an image.
    }

    public function postCropAvatar()
    {
        // take new croped image and save it to the public dir
        // return to orignal settings page
    }

    public function getChangeTheme()
    {
        $lessTemplate = base_path() .'/vendor/syntax/core/public/less/template.less';
        $userLess     = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'_css.less';

        // Make a copy of the less file
        if (!File::exists($userLess)) {
            File::copy($lessTemplate, $userLess);
        }

        $lines = file($userLess);

        $colors = array();

        $colors['grey']    = array('title' => 'Background Color',          'hex' => substr(explode('@grey: ',            $lines[4])[1],  0, -2));
        $colors['primary'] = array('title' => 'Primary Color',             'hex' => substr(explode('@primaryColor: ',    $lines[6])[1],  0, -2));
        $colors['info']    = array('title' => 'Information Color',         'hex' => substr(explode('@infoColor: ',       $lines[10])[1],  0, -2));
        $colors['success'] = array('title' => 'Success Color',             'hex' => substr(explode('@successColor: ',    $lines[13])[1], 0, -2));
        $colors['warning'] = array('title' => 'Warning Color',             'hex' => substr(explode('@warningColor: ',    $lines[16])[1], 0, -2));
        $colors['error']   = array('title' => 'Error Color',               'hex' => substr(explode('@errorColor: ',      $lines[19])[1], 0, -2));
        $colors['menu']    = array('title' => 'Active Menu Link Color',    'hex' => substr(explode('@menuColor: ',       $lines[22])[1], 0, -2));

        $this->setViewData('colors', $colors);
    }

    public function postChangeTheme()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            $userLess = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'_css.less';
            $userCss  = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'.css';

            $lines = file($userLess);

            // Set the new colors
            $lines[4]  = '@grey: '. $input['grey'] .";\n";
            $lines[6]  = '@primaryColor: '. $input['primary'] .";\n";
            $lines[10]  = '@infoColor: '. $input['info'] .";\n";
            $lines[13] = '@successColor: '. $input['success'] .";\n";
            $lines[16] = '@warningColor: '. $input['warning'] .";\n";
            $lines[19] = '@errorColor: '. $input['error'] .";\n";
            $lines[22] = '@menuColor: '. $input['menu'] .";\n";

            File::delete($userLess);
            File::delete($userCss);

            File::put($userLess, implode($lines));

            SSH::run(array(
                'cd '. base_path(),
                'lessc '. $userLess .' '. $userCss,
            ));

            Ajax::setStatus('success');
            return Ajax::sendResponse();
        }
    }

    public function getResetCss()
    {
        $userLess = public_path() .'/css/users/'. Str::studly($this->activeUser->username) .'.css';

        File::delete($userLess);

        $this->redirect('/user/account#change-theme', 'Your theme has been reset to the site default.');
    }
}