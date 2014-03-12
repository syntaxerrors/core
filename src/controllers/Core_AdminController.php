<?php

class Core_AdminController extends BaseController {

    public function getIndex()
    {
        // Get the collapse values
        if (!Session::has('COLLAPSE_ADMIN_PERMISSIONS')) {
            Session::put('COLLAPSE_ADMIN_PERMISSIONS', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_PERMISSIONS'));
        }
        if (!Session::has('COLLAPSE_ADMIN_GENERAL')) {
            Session::put('COLLAPSE_ADMIN_GENERAL', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_GENERAL'));
        }
        if (!Session::has('COLLAPSE_ADMIN_TYPES')) {
            Session::put('COLLAPSE_ADMIN_TYPES', $this->activeUser->getPreferenceValueByKeyName('COLLAPSE_ADMIN_TYPES'));
        }

        LeftTab::
            addPanel()
                ->setId('ADMIN_PERMISSIONS')
                ->setTitle('Permissions')
                ->setBasePath('admin')
                ->addTab('Users',           'users')
                ->addTab('Role Users',      'role-users')
                ->addTab('Roles',           'roles')
                ->addTab('Action Roles',    'action-roles')
                ->addTab('Actions',         'actions')
                ->buildPanel()
            ->addPanel()
                ->setId('ADMIN_GENERAL')
                ->setTitle('General')
                ->setBasePath('admin')
                ->addTab('User Preferences',    'user-preferences')
                ->addTab('Theme',               'theme')
                ->addTab('Migrations',          'migrations')
                ->addTab('Seeds',               'seeds')
                ->addTab('App Configs',         'app-configs')
                ->addTab('SQL Tables',          'sql-tables')
                ->buildPanel()
            ->addPanel()
                ->setId('ADMIN_TYPES')
                ->setTitle('Class Types')
                ->setBasePath('admin')
                ->addTab('Message',         'message')
                ->addTab('Forum Category',  'forum-category')
                ->addTab('Forum Board',     'forum-board')
                ->addTab('Forum Post',      'forum-post')
                ->addTab('Forum Reply',     'forum-reply')
                ->buildPanel()
            ->setCollapsable(true)
        ->make();
    }

    public function getSqlTables()
    {
        // Get the database from the config
        $databaseName = Config::get('database.connections')[Config::get('database.default')]['database'];

        // Get all tables in the database ordered by their name
        $tables = new Utility_Collection(
            DB::select(
                DB::raw('
                    SELECT table_name, engine
                    FROM information_schema.tables
                    WHERE table_type = \'BASE TABLE\'
                        AND table_schema=\''. $databaseName .'\'
                    ORDER BY table_name ASC'
                )
            )
        );
        $tables = $tables->table_name;

        $this->setViewData('tables', $tables);
    }

    public function getColumnsForSqlTable($table)
    {
        $columns = DB::select(DB::raw('describe '. $table));

        $this->setViewData('columns', $columns);
        $this->setViewData('table', $table);
    }

    public function getAppConfigs()
    {
        $configs = new Utility_Collection();

        $config              = new stdClass();
        $config->id          = 1;
        $config->name        = 'siteName';
        $config->value       = Config::get('app.siteName');

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 2;
        $config->name        = 'siteIcon';
        $config->value       = Config::get('app.siteIcon');

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 3;
        $config->name        = 'primaryRepo';
        $config->value       = Config::get('app.primaryRepo');

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 4;
        $config->name        = 'forumNews';
        $config->value       = Config::get('app.forumNews') ? 'true' : 'false';

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 5;
        $config->name        = 'menu';
        $config->value       = Config::get('app.menu');

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 6;
        $config->name        = 'devmode';
        $config->value       = Config::get('app.devmode') ? 'true' : 'false';

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 7;
        $config->name        = 'debug';
        $config->value       = Config::get('app.debug') ? 'true' : 'false';

        $configs->add($config);

        $config              = new stdClass();
        $config->id          = 8;
        $config->name        = 'url';
        $config->value       = Config::get('app.url');

        $configs->add($config);

        // Set up the one page crud main details
        Crud::setTitle('Configs')
            ->setSortProperty('name')
            ->setDeleteFlag(false)
            ->setResources($configs);

        // Add the display columns
        Crud::addDisplayField('name')
            ->addDisplayField('value');

        // Add the form fields
        Crud::addFormField('name', 'text')
            ->addFormField('value', 'text');

        Crud::make();
    }

    public function postAppConfigs()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            $configFile = app_path() .'/config/app.php';
            $config     = 'app.'. $input['name'];

            $lines = file($configFile);

            // Find the line being modified and change it
            foreach ($lines as $key => $line) {
                if (stripos($line, '\''. $input['name'] .'\'') !== false) {
                    $lines[$key] = "\t'". $input['name'] ."' => '". $input['value'] ."',\n";
                }
            }

            // Replace the contents of the config with the updated contents
            File::delete($configFile);

            File::put($configFile, implode($lines));

            // Set the temporary config so this page load has it
            Config::set($config, $input['value']);

            // Return the data to the view
            $return = array(
                'id' => $input['id'],
                'name' => $input['name'],
                'value' => Config::get($config)
            );

            Ajax::setStatus('success')->addData('resource', $return);

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getMigrations()
    {
        $updatePath       = app_path() .'/core/database/migrations/updates';
        $migrationUpdates = File::files($updatePath);
        $updates          = new Utility_Collection();

        foreach ($migrationUpdates as $key => $migrationUpdate) {
            $migrationUpdate   = str_replace(array($updatePath .'/', '.php'), '', $migrationUpdate);
            $existingMigration = Migration::where('migration', $migrationUpdate)->first();

            $update       = new stdClass();
            $update->id   = Str::random(10);
            $update->name = $migrationUpdate;
            $update->new  = $existingMigration == null ? true : false;

            $updates->add($update);
        }

        // Set the view data
        $this->setViewData('updates', $updates);
    }

    public function getRunMigrationUpdate($migration)
    {
        // Move the update file to local
        $updatePath   = app_path() .'/core/database/migrations/updates';
        $fullMigrationName = $migration .'.php';

        File::copy($updatePath .'/'. $fullMigrationName, app_path() .'/database/migrations/'. $fullMigrationName);
        exec('chmod 755 '. app_path() .'/database/migrations/'. $fullMigrationName);

        // Load the new file
        exec('/usr/local/bin/php '. base_path() .'/artisan dump-autoload');

        // Run the seed
        exec('/usr/local/bin/php '. base_path() .'/artisan migrate');

        $this->redirect('/admin#migrations', 'Migration ran successfully.');
    }

    public function getSeeds()
    {
        $updatePath  = app_path() .'/core/database/seeds/updates';
        $seedUpdates = File::files($updatePath);
        $updates     = new Utility_Collection();

        foreach ($seedUpdates as $key => $seedUpdate) {
            $seedUpdate   = str_replace(array($updatePath .'/', '.php'), '', $seedUpdate);
            $existingSeed = Seed::where('name', $seedUpdate)->first();

            $update       = new stdClass();
            $update->id   = Str::random(10);
            $update->name = $seedUpdate;
            $update->new  = $existingSeed == null ? true : false;

            $updates->add($update);
        }

        // Set the view data
        $this->setViewData('updates', $updates);
    }

    public function getRunSeedUpdate($seed)
    {
        // Add the seed to the table
        $newSeed       = new Seed;
        $newSeed->name = $seed;
        $this->save($newSeed);

        $this->redirect('/admin#seeds', 'Database successfully seeded.');
    }

    public function getUsers()
    {
        $users = User::orderBy('username', 'asc')->paginate(20);

        // Set up the one page crud main details
        Crud::setTitle('Users')
                 ->setSortProperty('username')
                 ->setDeleteLink('/admin/userdelete/')
                 ->setDeleteProperty('id')
                 ->setPaginationFlag(true)
                 ->setResources($users);

        // Add the display columns
        Crud::addDisplayField('username', '/user/view/', 'id')
                 ->addDisplayField('email', 'mailto');

        // Add the form fields
        Crud::addFormField('username', 'text', null, true)
                 ->addFormField('email', 'email', null, true)
                 ->addFormField('firstName', 'text')
                 ->addFormField('lastName', 'text')
                 ->addFormField('githubLogin', 'text')
                 ->addFormField('githubToken', 'text');

        // Handle the view data
        Crud::make();
    }

    public function postUsers()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $newPassword       = Str::random(15, 'all');
            $user              = (isset($input['id']) && strlen($input['id']) == 10 ? User::find($input['id']) : new User);
            $user->username    = $input['username'];
            $user->email       = $input['email'];
            $user->firstName   = $input['firstName'];
            $user->lastName    = $input['lastName'];
            $user->githubLogin = $input['githubLogin'];
            $user->githubToken = $input['githubToken'];
            $user->status_id   = 1;

            // Attempt to save the object
            $this->save($user);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $user->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getUserdelete($userId)
    {
        $this->skipView();

        $user = User::find($userId);
        $user->delete();

        return Redirect::back();
    }

    public function getResetpassword($userId)
    {
        $newPassword = Str::random(15, 'all');
        $user = User::find($userId);
        $user->password = $newPassword;
        $user->save();

        // Email them the new password
        $mailer          = IoC::resolve('phpmailer');
        $mailer->AddAddress($user->email, $user->username);
        $mailer->Subject = 'Password reset';
        $mailer->Body    = 'Your password has been reset for StygianVault.  Your new password is  '. $newPassword .'.  Once you log in, go to your profile to change this.';
        $mailer->Send();

        return Redirect::back();
    }

    public function getActions()
    {
        $actions = User_Permission_Action::orderBy('name', 'asc')->get();

        // Set up the one page crud
        Crud::setTitle('Actions')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/actiondelete/')
                 ->setDeleteProperty('id')
                 ->setResources($actions);

        // Add the display columns
        Crud::addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        Crud::addFormField('name', 'text')
                 ->addFormField('keyName', 'text')
                 ->addFormField('description', 'textarea');

        // Handle the view data
        Crud::make();
    }

    public function postActions()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $action              = (isset($input['id']) && $input['id'] != null ? User_Permission_Action::find($input['id']) : new User_Permission_Action);
            $action->name        = $input['name'];
            $action->keyName     = $input['keyName'];
            $action->description = $input['description'];

            // Attempt to save the object
            $this->save($action);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $action->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getActiondelete($actionId)
    {
        $this->skipView();

        $action = User_Permission_Action::find($actionId);
        $action->roles()->detach();
        $action->delete();

        return Redirect::to('/admin#actions');
    }

    public function getRoles()
    {
        $roles = User_Permission_Role::orderBy('group', 'asc')->orderBy('priority', 'asc')->get();

        // Set up the one page crud
        Crud::setTitle('Roles')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/roledelete/')
                 ->setDeleteProperty('id')
                 ->setResources($roles);

        // Add the display columns
        Crud::addDisplayField('group')
                 ->addDisplayField('name')
                 ->addDisplayField('keyName')
                 ->addDisplayField('priority');

        // Add the form fields
        Crud::addFormField('group', 'text', null, true)
                 ->addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true)
                 ->addFormField('description', 'textarea')
                 ->addFormField('priority', 'text');

        // Handle the view data
        Crud::make();
    }

    public function postRoles()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $role              = (isset($input['id']) && $input['id'] != null ? User_Permission_Role::find($input['id']) : new User_Permission_Role);
            $role->group       = $input['group'];
            $role->name        = $input['name'];
            $role->keyName     = $input['keyName'];
            $role->description = $input['description'];
            $role->priority    = $input['priority'];

            // Attempt to save the object
            $this->save($role);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $role->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getRoledelete($roleId)
    {
        $this->skipView();

        $role = User_Permission_Role::find($roleId);
        $role->actions()->detach();
        $role->users()->detach();
        $role->delete();

        return Redirect::to('/admin#roles');
    }

    public function getRoleUsers()
    {
        $users     = User::orderBy('username', 'asc')->paginate(10);
        $roles     = User_Permission_Role::orderByNameAsc()->get();

        $usersArray = $this->arrayToSelect($users, 'id', 'username', 'Select a user');
        $rolesArray = $this->arrayToSelect($roles, 'id', 'name', 'None');

        Crud::setTitle('Role Users')
            ->setSortProperty('username')
            ->setPaginationFlag(true)
            ->setUpMultiColumn()
                ->addRootColumn('Users', $users, 'username', 'user_id', $usersArray)
                ->addMultiColumn('Roles', 'roles', 'name', 'role_id', $rolesArray)
                ->finish()
            ->make();
    }

    public function postRoleUsers()
    {
        $this->skipView();

        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Remove all existing roles
            $roleUsers = User_Permission_Role_User::where('user_id', $input['user_id'])->get();

            if ($roleUsers->count() > 0) {
                foreach ($roleUsers as $roleUser) {
                    $roleUser->delete();
                }
            }

            // Add any new roles
            if (count($input['role_id']) > 0) {
                foreach ($input['role_id'] as $roleId) {
                    if ($roleId == '0') continue;

                    $roleUser = new User_Permission_Role_User;
                    $roleUser->user_id = $input['user_id'];
                    $roleUser->role_id = $roleId;

                    $this->save($roleUser);
                }
            }

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
                $user = User::find($input['user_id']);

                $main = $user->toArray();
                $main['multi'] = $user->roles->id->toJson();

                Ajax::setStatus('success')
                                    ->addData('resource', $user->roles->toArray())
                                    ->addData('main', $main);
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getActionRoles()
    {
        $roles   = User_Permission_Role::orderByNameAsc()->get();
        $actions = User_Permission_Action::orderByNameAsc()->get();

        $rolesArray = $roles->toSelectArray('Select a role');
        $actionsArray = $actions->toSelectArray('None');

        // Set up the one page crud
        Crud::setTitle('Action Roles')
            ->setSortProperty('name')
            ->setUpMultiColumn()
                ->addRootColumn('Roles', $roles, 'name', 'role_id', $rolesArray)
                ->addMultiColumn('Actions', 'actions', 'name', 'action_id', $actionsArray)
                ->finish()
            ->make();
    }

    public function postActionRoles()
    {
        $this->skipView();

        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Remove all existing roles
            $actionRoles = User_Permission_Action_Role::where('role_id', $input['role_id'])->get();

            if ($actionRoles->count() > 0) {
                foreach ($actionRoles as $actionRole) {
                    $actionRole->delete();
                }
            }

            // Add any new roles
            if (count($input['action_id']) > 0) {
                foreach ($input['action_id'] as $actionId) {
                    if ($actionId == '0') continue;

                    $actionRole            = new User_Permission_Action_Role;
                    $actionRole->role_id   = $input['role_id'];
                    $actionRole->action_id = $actionId;

                    $this->save($actionRole);
                }
            }

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
                $role = User_Permission_Role::find($input['role_id']);

                $main = $role->toArray();
                $main['multi'] = $role->actions->id->toJson();

                Ajax::setStatus('success')
                                   ->addData('resource', $role->actions->toArray())
                                   ->addData('main', $main);
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getUserPreferences()
    {
        $preferences     = User_Preference::orderBy('hiddenFlag', 'asc')->orderByNameAsc()->get();

        // Set up the one page crud main details
        
        Crud::setTitle('User Preferences')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/preferencedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($preferences);

        // Add the display columns
        Crud::addDisplayField('name')
                 ->addDisplayField('value')
                 ->addDisplayField('default')
                 ->addDisplayField('hidden');

        // Add the form fields
        Crud::addFormField('name', 'text')
                 ->addFormField('keyName', 'text')
                 ->addFormField('value', 'text')
                 ->addFormField('default', 'text')
                 ->addFormField('display', 'select', array(null => 'Select a type', 'text' => 'Text', 'textarea' => 'Textarea', 'select' => 'Select', 'radio' => 'Radio'))
                 ->addFormField('description', 'textarea')
                 ->addFormField('hiddenFlag', 'select', array('Not Hidden', 'Hidden'));

        // Handle the view data
        Crud::make();
    }

    public function postUserPreferences()
    {
        $this->skipView();

        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $preference              = (isset($input['id']) && $input['id'] != null ? User_Preference::find($input['id']) : new User_Preference);
            $preference->name        = $input['name'];
            $preference->keyName     = $input['keyName'];
            $preference->description = $input['description'];
            $preference->value       = $input['value'];
            $preference->default     = $input['default'];
            $preference->display     = $input['display'];
            $preference->hiddenFlag  = $input['hiddenFlag'];

            // Attempt to save the object
            $this->save($preference);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $preference->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getPreferencedelete($preferenceId)
    {
        $this->skipView();

        $preference = User_Preference::find($preferenceId);
        $preference->delete();

        return Redirect::to('/admin#preferences');
    }

    public function getTheme()
    {
        $masterLess   = public_path() .'/css/colors.less';

        $lines = file($masterLess);

        // ppd($lines);

        $colors = array();

        $colors['grey']    = array('title' => 'Background Color',          'hex' => substr(explode('@grey: ',            $lines[0])[1],  0, -2));
        $colors['primary'] = array('title' => 'Primary Color',             'hex' => substr(explode('@primaryColor: ',    $lines[2])[1],  0, -2));
        $colors['info']    = array('title' => 'Information Color',         'hex' => substr(explode('@infoColor: ',       $lines[6])[1],  0, -2));
        $colors['success'] = array('title' => 'Success Color',             'hex' => substr(explode('@successColor: ',    $lines[9])[1],  0, -2));
        $colors['warning'] = array('title' => 'Warning Color',             'hex' => substr(explode('@warningColor: ',    $lines[12])[1], 0, -2));
        $colors['error']   = array('title' => 'Error Color',               'hex' => substr(explode('@errorColor: ',      $lines[15])[1], 0, -2));
        $colors['menu']    = array('title' => 'Active Menu Link Color',    'hex' => substr(explode('@menuColor: ',       $lines[18])[1], 0, -2));

        $this->setViewData('colors', $colors);
    }

    public function postTheme()
    {
        $input = e_array(Input::all());

        if ($input != null) {
            $masterLess   = public_path() .'/css/colors.less';

            $lines = file($masterLess);

            // Set the new colors
            $lines[0]  = '@grey: '. $input['grey'] .";\n";
            $lines[2]  = '@primaryColor: '. $input['primary'] .";\n";
            $lines[6]  = '@infoColor: '. $input['info'] .";\n";
            $lines[9]  = '@successColor: '. $input['success'] .";\n";
            $lines[12] = '@warningColor: '. $input['warning'] .";\n";
            $lines[15] = '@errorColor: '. $input['error'] .";\n";
            $lines[18] = '@menuColor: '. $input['menu'] .";\n";

            File::delete($masterLess);

            File::put($masterLess, implode($lines));

            SSH::run(array(
                'cd '. base_path(),
                'gulp css'
            ));

            Ajax::setStatus('success');
            return Ajax::sendResponse();
        }
    }

    public function getMessage()
    {
        $messageTypes = Message_Type::orderByNameAsc()->get();

        // Set up the one page crud
        Crud::setTitle('Message Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/messagetypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($messageTypes);

        // Add the display columns
        Crud::addDisplayField('name');

        // Add the form fields
        Crud::addFormField('name', 'text')
                 ->addFormField('keyName', 'text');

        // Handle the view data
        Crud::make();
    }

    public function postMessage()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $messageType          = (isset($input['id']) && $input['id'] != null ? Message_Type::find($input['id']) : new Message_Type);
            $messageType->name    = $input['name'];
            $messageType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($messageType);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $messageType->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getMessagetypedelete($messageTypeId)
    {
        $this->skipView();

        $messageType = Message_Type::find($messageTypeId);
        $messageType->delete();

        return Redirect::to('/admin#messagetypes');
    }

    public function getForumCategory()
    {
        $categoryTypes = Forum_Category_Type::orderByNameAsc()->get();

        // Set up the one page crud
        Crud::setTitle('Category Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/categorytypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($categoryTypes);

        // Add the display columns
        Crud::addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        Crud::addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        // Handle the view data
        Crud::make();
    }

    public function postForumCategory()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $categoryType          = (isset($input['id']) && $input['id'] != null ? Forum_Category_Type::find($input['id']) : new Forum_Category_Type);
            $categoryType->name    = $input['name'];
            $categoryType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($categoryType);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $categoryType->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getCategorytypedelete($categoryTypeId)
    {
        $this->skipView();

        $categoryType = Forum_Category_Type::find($categoryTypeId);
        $categoryType->delete();

        return Redirect::to('/admin#categorytypes');
    }

    public function getForumBoard()
    {
        $boardTypes = Forum_Board_Type::orderByNameAsc()->get();

        // Set up the one page crud
        Crud::setTitle('Board Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/boardtypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($boardTypes);

        // Add the display columns
        Crud::addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        Crud::addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        // Handle the view data
        Crud::make();
    }

    public function postForumBoard()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $boardType          = (isset($input['id']) && $input['id'] != null ? Forum_Board_Type::find($input['id']) : new Forum_Board_Type);
            $boardType->name    = $input['name'];
            $boardType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($boardType);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $boardType->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getBoardtypedelete($boardTypeId)
    {
        $this->skipView();

        $boardType = Forum_Board_Type::find($boardTypeId);
        $boardType->delete();

        return Redirect::to('/admin#boardtypes');
    }

    public function getForumPost()
    {
        $postTypes = Forum_Post_Type::orderByNameAsc()->get();

        // Set up the one page crud
        Crud::setTitle('Post Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/posttypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($postTypes);

        // Add the display columns
        Crud::addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        Crud::addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        // Handle the view data
        Crud::make();
    }

    public function postForumPost()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $postType          = (isset($input['id']) && $input['id'] != null ? Forum_Post_Type::find($input['id']) : new Forum_Post_Type);
            $postType->name    = $input['name'];
            $postType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($postType);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $postType->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getPosttypedelete($postTypeId)
    {
        $this->skipView();

        $postType = Forum_Post_Type::find($postTypeId);
        $postType->delete();

        return Redirect::to('/admin#posttypes');
    }

    public function getForumReply()
    {
        $replyTypes = Forum_Reply_Type::orderByNameAsc()->get();

        // Set up the one page crud
        Crud::setTitle('Reply Types')
                 ->setSortProperty('name')
                 ->setDeleteLink('/admin/replytypedelete/')
                 ->setDeleteProperty('id')
                 ->setResources($replyTypes);

        // Add the display columns
        Crud::addDisplayField('name')
                 ->addDisplayField('keyName');

        // Add the form fields
        Crud::addFormField('name', 'text', null, true)
                 ->addFormField('keyName', 'text', null, true);

        // Handle the view data
        Crud::make();
    }

    public function postForumReply()
    {
        $this->skipView();
        // Set the input data
        $input = e_array(Input::all());

        if ($input != null) {
            // Get the object
            $replyType          = (isset($input['id']) && $input['id'] != null ? Forum_Reply_Type::find($input['id']) : new Forum_Reply_Type);
            $replyType->name    = $input['name'];
            $replyType->keyName = $input['keyName'];

            // Attempt to save the object
            $this->save($replyType);

            // Handle errors
            if ($this->errorCount() > 0) {
                Ajax::addErrors($this->getErrors());
            } else {
               Ajax::setStatus('success')->addData('resource', $replyType->toArray());
            }

            // Send the response
            return Ajax::sendResponse();
        }
    }

    public function getReplytypedelete($replyTypeId)
    {
        $this->skipView();

        $replyType = Forum_Reply_Type::find($replyTypeId);
        $replyType->delete();

        return Redirect::to('/admin#replytypes');
    }
}
