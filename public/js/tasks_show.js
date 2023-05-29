import {URL, taskList, updateTaskList, checkClick, close } from './common_methods.js'

document.addEventListener('DOMContentLoaded', function () {
    let thCompleteList

    let searchForm;

    //Popup
    let popup
    let popupInput;
    let acceptBtnBtn;
    let closeBtn;


    const main = () => {
        prepareDOMElements()
        prepareDOMEvents()
    }

    let prepareDOMElements = () => {
        searchForm = document.querySelector('#search-form');
        thCompleteList = document.querySelectorAll('.complete')

        popup = document.querySelector('.popup')
        popupInput = document.querySelector('.popup-input');
        acceptBtnBtn = document.querySelector('.accept');
        closeBtn = document.querySelector('.close');
    };


    const prepareDOMEvents = () => {
        searchForm.addEventListener('submit', submit)
        taskList.addEventListener('click', checkClick)
        closeBtn.addEventListener('click', close)
    };

    const submit = e => {
        e.preventDefault();
        let formData = new FormData(e.target);

        fetch(`${URL}/tasks`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                updateTaskList(data);
                console.log('The task list was displayed correctly.');
                e.target.reset();
            })
            .catch(error => {
                console.log('An error occurred during the request.');
                console.log(error);
                if (error instanceof NotFoundHttpException) {
                    alert('Error: Task not found');
                } else {
                    alert('An error occurred during the request');
                }
            });
    };

    main();

});


// Te same funkcje w JQUERY

// $(document).ready(function () {
//     const URL = 'http://127.0.0.1:8000';
//
//     let taskDetails
//     let taskId
//
//     const popupInput = document.querySelector('.popup-input')
//
//     $(document).on('click', '.td-detail',function () {
//         taskDetails =$(this)
//         taskId = $(this).data('id')
//         $('.popup-input').val(taskDetails.text())
//         $('.popup').css('display', 'flex')
//     })
//
//     $('.cancel').on('click', function () {
//         $('.popup').css('display', 'none')
//     })
//
//     $('.accept').on('click', function () {
//         if ( $('.popup-input').val() !== ''){
//             taskDetails.text(popupInput.value)
//             $('.popup').css('display', 'none')
//         }
//
//         $.ajax({
//             url: `${URL}/tasks/${taskId}`,
//             type: 'PUT',
//             data: {details : taskDetails.text()},
//             success: function (response) {
//                 console.log('New task details are saved in the database:', response)
//             },
//             error: function (error) {
//                 console.error('An error occurred while saving changes to the database:', error);
//             }
//         })
//
//     })
//
//
//     $('#search-form').submit(function (e) {
//         e.preventDefault();
//
//         let formData = new FormData(this);
//
//         $.ajax({
//             url: `${URL}/tasks`,
//             method: 'POST',
//             data: formData,
//             processData: false,
//             contentType: false,
//             success: function (response) {
//                 updateTaskList(response);
//                 console.log('The task list was displayed correctly.');
//
//             },
//             error: function (error) {
//                 console.log('An error occurred during the request.');
//                 console.log(error);
//             }
//         });
//     });
//
//     const updateTaskList = tasks => {
//         const taskList = $('#task-list');
//         taskList.empty();
//
//         createTableHead(taskList);
//         createTableBody(taskList, tasks);
//     }
//
//     const createTableHead = taskList => {
//         const tr = $('<tr>');
//         const title = $('<th>').text('Task title');
//         const details = $('<th>').text('Details');
//         const deadline = $('<th>').text('Deadline');
//         const completed = $('<th>').text('Completed');
//
//         tr.append(title, details, deadline, completed);
//         taskList.append(tr);
//     }
//
//     const createTableBody = (taskList, tasks) => {
//         tasks.forEach(function (task) {
//             const tr = $('<tr>');
//             const title = $('<td>').text(task.title);
//             const details = $('<td>').text(task.details);
//             details.addClass('td-detail')
//             details.attr('data-id', task.id);
//             const deadline = $('<td>').text(task.deadline);
//             const completed = $('<td>').html(task.completed ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>');
//             completed.addClass('td-complete')
//             completed.addClass(task.completed ? 'green-completed' : 'red-completed')
//             completed.attr('data-id', task.id);
//
//             tr.append(title, details, deadline, completed);
//             taskList.append(tr);
//         });
//     }
//
//     $('#task-list').on('click', '.td-complete', function () {
//         let taskId = $(this).data('id')
//         let isCompleted = $(this).hasClass('green-completed')
//
//         $(this).toggleClass('green-completed red-completed')
//             .html($(this).hasClass('green-completed') ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>')
//
//         $.ajax({
//             url: `${URL}/tasks/${taskId}`,
//             type: 'PUT',
//             data: {completed: isCompleted},
//             success: function (response) {
//                 console.log('Changes saved in the database:', response);
//             },
//             error: function (error) {
//                 console.error('An error occurred while saving changes to the database:', error);
//             }
//         })
//     })
// });