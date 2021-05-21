<?php defined('INFOX') or die('No direct access allowed.');
include('common_jquery.php'); ?>
<section class="content-header">
    <h1>Institutions <small>add/manage</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">institutions</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="staticBackdropLabel">Add new Institution!</h4>
                </div>
                <div class="modal-body">
                    <form id="add_new_inst">
                        <div class="form-group">
                            <input type="tel" class="form-control" name="code" placeholder="Code" required onkeypress="return isNumberKey(event)" pattern="[0-9]{6,32}">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="prefix" placeholder="Prefix : Capital <3-5>" required pattern="[A-Z]{3,5}">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Name {UPPERCASE} at least 6 char" required pattern="[A-Z ].{5,}">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="address" placeholder="Address" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email address" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" onkeypress="return isNumberKey(event)" name="contact" placeholder="Contact" required pattern="[0-9]{10}">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <script>
                        $(document).ready(() => {
                            $("#add_new_inst").submit(function(e) {
                                e.preventDefault(); // avoid to execute the actual submit of the form.
                                var data = $(this).serializeObject()
                                $(this).hide();
                                axios.post('/institutions?action=add', data).then((response) => {
                                    $("#add_new_inst").show()
                                    load_in_table(response.data);
                                    document.getElementById("add_new_inst").reset();
                                    window.$('#staticBackdrop').modal('hide');
                                }).catch((err) => {
                                    $("#add_new_inst").show()
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- the form to be viewed as dialog-->
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table m-0 p-0 table-striped table-sm text-center ">
                <thead class="thead-dark">
                    <tr>
                        <th>S1 No.</th>
                        <th>Prefix</th>
                        <th>Name &amp;Address</th>
                        <th>code</th>
                        <th colspan=4>-course-manager-staff-user- (limits)</th>
                        <th colspan=2>Status</th>
                        <th colspan=2>Action</th>
                    </tr>
                </thead>
                <tbody id="institution_list">
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">

            <span class="pull-right"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop">Add New </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="load_data()">Reload</button></span>


        </div>
    </div>
    <script>
        var load_in_table_key = 0;
        var savedornot = ['YASIM'];
        window.onbeforeunload = function() {
            if (savedornot.length != 1) {
                return 'There are unsaved changes. Are you sure you want to leave?';
            }
        }

        function eventer(elem) {
            $(elem).find("input:disabled").attr("disabled", false).focus();
        }

        function eventercheck(elem, uid) {
            var length = elem.value.length;
            var oldvalue = $(elem).attr('data-value');
            if (length == 0) {
                elem.value = oldvalue;
            }
            if (elem.value == oldvalue) {
                $(elem).attr("disabled", true);
                let pos = savedornot.indexOf(uid);
                savedornot.splice(pos, 1);
            } else {
                let pos = savedornot.indexOf(uid);
                if (pos == -1) {
                    savedornot.push(uid);
                }
            }
        }

        function load_in_table(value) {
            load_in_table_key += 1
            var html = "";
            html += '<tr id="' + value.u_id + '">';
            html += '<th>' + load_in_table_key + '</th>';
            html += '<td>' + value.u_prefix + '</td>';
            html += '<td style="word-wrap:break-word;text-align:left;"><b>' + value.u_name + '</b><br/>';
            html += value.u_address + '<br/>' + value.u_email + ',' + value.u_contact + '</td>';
            html += '<td>' + value.code + '</td>';
            html +=
                '<td><div onclick="eventer(this)">#<input type="text" onkeypress="return isNumberKey(event)" onblur="eventercheck(this,' +
                "'" + value.u_id + "'" + ')"  size=2 name="course"  disabled   value="' + value.u_course_limit +
                '" data-value="' + value
                .u_course_limit + '"></div></td>';
            html +=
                '<td><div onclick="eventer(this)">#<input type="text" onkeypress="return isNumberKey(event)" onblur="eventercheck(this,' +
                "'" + value.u_id + "'" + ')" size=2 name="manager"  disabled   value="' + value.u_manager_limit +
                '" data-value="' + value
                .u_manager_limit + '"></div></td>';
            html +=
                '<td><div onclick="eventer(this)">#<input type="text" onkeypress="return isNumberKey(event)" onblur="eventercheck(this,' +
                "'" + value.u_id + "'" + ')" size=2 name="staff"  disabled   value="' + value.u_staff_limit +
                '" data-value="' + value
                .u_staff_limit + '"></div></td>';
            html +=
                '<td><div onclick="eventer(this)">#<input type="text" onkeypress="return isNumberKey(event)" onblur="eventercheck(this,' +
                "'" + value.u_id + "'" + ')"  size=2 name="user"  disabled   value="' + value.u_user_limit +
                '" data-value="' + value.u_user_limit +
                '"></div></td>';
            if (value.suspended == 0) {
                if (value.active == 0) {
                    html += '<td><button class="btn btn-xs btn-success mx-1" onclick="activate_institution(' + "'" + value.u_id +
                        "'" + ')">Activate</button></td>';
                } else {
                    html += '<td><button class="btn btn-xs btn-warning mx-1" onclick="deactivate_institution(' + "'" + value
                        .u_id + "'" + ')">Deactivate</button></td>';
                }
                html += '<td><button class="btn btn-xs btn-danger mx-1" onclick="suspend_institution(' + "'" + value.u_id + "'" +
                    ')">suspend</button></td>';

                html += '<td colspan="2"><button class="btn btn-xs btn-info mx-1" onclick="institution_limit(' + "'" + value
                    .u_id + "'" + ')">LIMIT</button></td>';
            } else {
                html += '<td colspan="4">SUSPENDED/DELETED</td>';
            }
            html += '</tr>';
            $('#institution_list').append(html);
        }

        function load_data() {
            load_in_table_key = 0;
            $('#institution_list').empty();
            axios.get('/institutions').then((response) => {
                alertify.success('Institution List Loaded');
                $(response.data).each((key, value) => {
                    load_in_table(value)
                });
            })
        }

        function activate_institution(uid) {
            alertify.confirm('activate_institution', 'activate_institution', function() {
                axios.post('institutions?action=activate', {
                    id: uid
                }).then((response) => {
                    alertify.success(`${response.data}`);
                    load_data();
                })
            }, function() {
                alertify.error('activate_institution Canceled')
            });
        }

        function deactivate_institution(uid) {
            alertify.confirm('deactivate_institution', 'deactivate_institution', function() {
                axios.post('institutions?action=deactivate', {
                    id: uid
                }).then((response) => {
                    alertify.success(`${response.data}`);
                    load_data();
                })
            }, function() {
                alertify.error('deactivate_institution Canceled')
            });
        }

        function suspend_institution(uid) {
            alertify.confirm('suspend_institution', 'suspend_institution', function() {
                axios.post('institutions?action=suspend', {
                    id: uid
                }).then((response) => {
                    alertify.success(`${response.data}`);
                    load_data();
                })
            }, function() {
                alertify.error('suspend_institution Canceled')
            });
        }

        function institution_limit(uid) {
            var course = $('#' + uid + ' input[name="course"]');
            var manager = $('#' + uid + ' input[name="manager"]');
            var staff = $('#' + uid + ' input[name="staff"]');
            var user = $('#' + uid + ' input[name="user"]');
            if (course.val() != course.attr('data-value') || manager.val() != manager.attr('data-value') || staff.val() != staff.attr('data-value') || user.val() != user.attr('data-value')) {
                axios.post('/institutions?action=limit', {
                    'id': uid,
                    'course': course.val(),
                    'manager': manager.val(),
                    'staff': staff.val(),
                    'user': user.val()
                }).then((response) => {
                    $(`#${uid} input`).attr("disabled", true);
                    let pos = savedornot.indexOf(uid);
                    savedornot.splice(pos, 1);
                    course.attr('data-value', course.val());
                    manager.attr('data-value', manager.val());
                    staff.attr('data-value', staff.val());
                    user.attr('data-value', user.val());
                    alertify.success('Limits Updated');
                })
            } else {
                alertify.alert("Limit Validator", "Not Changes");
            }
        }
        $(document).ready(() => {
            load_data();
        });
    </script>
</section>