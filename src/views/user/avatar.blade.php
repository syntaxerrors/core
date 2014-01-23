<div class="row">
    <div class="col-md-12" id="listPanel">
        {{ bForm::ajaxForm('avatarPreferenceForm', 'Your preference has been updated.')->open() }}
             <div class="panel panel-default">
                <div class="panel-heading">
                    Avatar
                    <div class="panel-btn">
                        <a href="javascript:void(0);" onClick="addPanel()">Upload an avatar</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-5">
                            <div class="well text-center">
                                <div class="well-title">Avatar</div>
                                {{ HTML::image($activeUser->avatar, null, array('class'=> 'media-object', 'style' => 'width: 150px;margin: 0 auto;')) }}
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="well text-center">
                                <div class="well-title">Gravatar</div>
                                {{ HTML::image($activeUser->onlyGravatar, null, array('class'=> 'media-object', 'style' => 'width: 150px;margin: 0 auto;')) }}
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ bForm::hidden('avatar_preference_id', $avatarPreference->id) }}
                            {{ bForm::select('avatar_preference', $preferenceArray, $avatarPreference->value, array(), 'Select what to display', 2) }}
                            {{ bForm::jsonSubmit('Save') }}
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <small>This site uses <a href="http://www.gravatar.com" target="_blank">Gravatar</a>. You may upload a single avatar for use in the site or use the gravatar or a default image.  You can select which you prefer at any time.</small>
                    <hr />
                    <div id="message"></div>
                </div>
            </div>
        {{ bForm::close() }}
    </div>
    {{ bForm::open(true, array('id' => 'avatarFileForm')) }}
        <div class="col-md-4" style="display: none;" id="fileForm">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Upload an avatar
                    <div class="panel-btn">
                        <a href="javascript: void(0);" onClick="addPanel()"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    {{ bForm::image('avatar', $activeUser->image, 'New Avatar') }}
                    {{ bForm::submit('Save') }}
                </div>
            </div>
        </div>
    {{ bForm::close() }}
</div>

@section('js')
    <script>
        function addPanel() {
            $('#listPanel').toggleClass('col-md-12').toggleClass('col-md-8');
            if ($('#fileForm').css('display') == 'none') {
                $('#fileForm').show();
            } else {
                $('#fileForm').hide();
                $('#avatarFileForm .error').removeClass('error');
                $('#avatarFileForm #message').empty();
            }
        }
    </script>
@stop