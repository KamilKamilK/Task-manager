import {
    URL,
    taskList,
    titleTd,
    detailTd,
    deadlineTd,
    titleInput,
    detailInput,
    deadlineInput,
    target,
    updateTaskList,
    checkClick,
    close,
    saveToDB,
    showMessage

} from './common_methods.js'

document.addEventListener('DOMContentLoaded', function () {

    let addBtn;

    //Popup
    let popup;
    let acceptBtnBtn;
    let cancelBtn;
    let deleteBtn;
    let popupInfo;

    let infoMessageElement

    const main = () => {
        prepareDOMElements();
        prepareDOMEvents();
    }

    const prepareDOMElements = () => {
        // addBtn = document.querySelector('#add_task');
        cancelBtn = document.querySelector('.cancel');
        acceptBtnBtn = document.querySelector('.accept');
        popup = document.querySelector('.popup');
        deleteBtn = document.querySelector('.delete');
        popupInfo = document.querySelector('.popup-info');
        infoMessageElement = document.querySelector('.info-message')
    };


    const prepareDOMEvents = () => {
        // addBtn.addEventListener('submit', addNewTask);
        taskList.addEventListener('click', checkClick);
        cancelBtn.addEventListener('click', close);
        acceptBtnBtn.addEventListener('click', accept);
        deleteBtn.addEventListener('click', deleteTask);
    };


    // DODAWANIE TASKA Z UŻYCIEM JS
    // const addNewTask = e => {
    //     let formData = new FormData(e.target);
    //     fetch(`${URL}/task/add`, {
    //         method: 'POST',
    //         body: formData
    //     })
    //         .then(response => {
    //             if (response.ok) {
    //                 return response.json();
    //             } else {
    //                 throw new Error('An error occurred during the request')
    //             }
    //         })
    //         .then(data => {
    //             // updateTaskList(data, true);
    //             console.log('The task list was updated correctly.');
    //         })
    //         .catch(error => {
    //             console.log('An error occurred during the request.');
    //             console.log(error);
    //         });
    // }

    const accept = () => {
        
        popupInfo.textContent = ' '
        if (invalidDateFormat(deadlineInput.value)[0] === true) {
            popupInfo.textContent = invalidDateFormat(deadlineInput.value)[1]
            return
        }

        if (titleInput.value === '') {
            popupInfo.textContent = 'You cannot leave empty space for the title'
            return
        }

        if (detailInput.value === '') {
            popupInfo.textContent = 'You cannot leave empty space for the details'
            return
        }

        titleTd.textContent = titleInput.value;
        detailTd.textContent = detailInput.value;
        deadlineTd.textContent = deadlineInput.value;

        let taskId = target.dataset.id;
        let isCompleted = target.closest('tr').querySelector('.complete').classList.contains('green-completed');
        let title = titleInput.value
        let detail = detailInput.value
        let deadline = deadlineInput.value;

        console.log(taskId, isCompleted, title, detail, deadline)
        saveToDB(taskId, isCompleted, title, detail, deadline);
        close();
    }

    const deleteTask = () => {
        let taskId = target.closest('tr').querySelector('.complete').dataset.id;
        target.closest('tr').remove()
        deleteTaskFromDatabase(taskId)
        close()
    }

    const deleteTaskFromDatabase = (taskId) => {

        fetch(`${URL}/tasks/${taskId}/delete`, {
            method: 'DELETE',
            body: JSON.stringify({taskId: taskId})
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('An error occurred during the request')
                }
            })
            .then(data => {
                updateTaskList(data['tasks'], true);
                console.log('The task was deleted correctly.');
                showMessage('Task deleted successfully', 'success')
            })
            .catch(error => {
                console.log('An error occurred during the deletion.');
                console.log(error);
                showMessage('Something wrong went with deletion', 'error')
            });
    }

    const invalidDateFormat = (dataValue) => {
        const dateRegex = /^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[0-2])\.\d{4}$/;        let todaysDate = new Date()
        let enteredDateParts = dataValue.split('.');

        let enteredDate = new Date(
            enteredDateParts[2],
            enteredDateParts[1] - 1,
            enteredDateParts[0]
        )

        if (!dateRegex.test(dataValue)) {
            return [true, 'Invalid date format. Proper date format: dd.mm.yyyy'];
        } else if (todaysDate > enteredDate) {
            return [true, 'The Entered date is in the past. Proper date format: dd.mm.yyyy'];
        }

        return false
    }

    main();

});