//CUSTOM JS
$('#userEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data('user');

    let modal = $(this);

    modal.find('#userEditId').val(user.id);
    modal.find('#userEditName').text(user.name);
    modal.find('#userEditRole').val(user.role);
});

$('#userEditModalAjax').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data('user');

    let modal = $(this);

    modal.find('#userEditIdAjax').val(user.id);
    modal.find('#userEditNameAjax').text(user.name);
    modal.find('#userEditRoleAjax').val(user.role);
});

$('#userDeleteModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data('user');

    let modal = $(this);

    modal.find('#userDeleteId').val(user.id);
    modal.find('#userDeleteName').text(user.name);
});

/**
 * Update user using ajax
 */
$(document).ready(function() {
    $('#userEditButtonAjax').on('click', function() {
        $('#userEditAlert').addClass('hidden');

        let id = $('#userEditIdAjax').val();
        let role = $('#userEditRoleAjax').val();

        $.ajax({
            method: 'POST',
            url: '/user-update/' + id,
            data: {role: role}
        }).done(function(response) {
            if (response.error !== '') {
                $('#userEditAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#userDeleteButton').on('click', function() {
        $('#userDeleteAlert').addClass('hidden');
        let id = $('#userDeleteId').val();

        $.ajax({
            method: 'POST',
            url: '/user/delete/' + id
        }).done(function(response) {
            if (response.error !== '') {
                $('#userDeleteAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#changeBoard').on('change', function() {
        let id = $(this).val();

        window.location.href = '/board/' + id;
    });
});

/**
 * Delete Board
 */


 $('#boardDeleteModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let board = button.data('board');

    let modal = $(this);

    modal.find('#boardDeleteId').val(board.id);
    modal.find('#boardDeleteName').text(board.name);
});

/**
 * Edit Board Modal
 */

$('#boardEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let board = button.data('board');

    let modal = $(this);

    modal.find('#boardEditId').val(board.id);
    modal.find('#boardEditTitle').val(board.name);


    let board_members = board.board_users;
    let values =[] ;
    for(key in board_members ){
        // console.log(board_members[key].user_id);
         var option = document.createElement("option");
         option.text = board_members[key].user_id;
         document.getElementById('boardEditMembers').add(option);

         values.push(board_members[key].user_id);
    }
   // console.log(values);
    modal.find('#boardEditMembers').val(values);

});


$(document).ready(function() {

    $('#boardDeleteButton').on('click', function() {
        $('#boardDeleteAlert').addClass('hidden');
        let id = $('#boardDeleteId').val();

        $.ajax({
            method: 'POST',
            url: '/board/delete/' + id
        }).done(function(response) {
            if (response.error !== '') {
                $('#boardDeleteAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });
    $('#userEditButtonAjax').on('click', function() {
        $('#boardEditAlert').addClass('hidden');

        let id = $('#boardEditId').val();
        let title = $('#boardEditTitle').val();
        let members = $('#boardEditMembers').val();

        $.ajax({
            method: 'POST',
            url: '/board/update/' + id,
            data: {title: title, members: members}
        }).done(function(response) {
            if (response.error !== '') {
                $('#boardEditAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

});



/**
 * Edit Task
 */


 $('#taskEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data('task');
    let users = button.data('user');

    let modal = $(this);

    modal.find('#taskEditId').val(task.id);
    modal.find('#taskEditName').val(task.name);
    modal.find('#taskEditDescription').val(task.description);

    //modal.find('#taskEditAssignment').val(task.assignment);
    //modal.find('#taskEditStatus').val(task.status);
    console.log('users: ');
    console.log(users);
    for(key in users ){
        var optionAssignment = document.createElement("option");
        optionAssignment.text = users[key].name;
        //optionAssignment.setAttribute('value', users[key].id);
        document.getElementById('taskEditAssignment').add(optionAssignment);
        console.log(users[key].name);
    }


});


