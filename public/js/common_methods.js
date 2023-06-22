const URL = `http://${window.location.host}`;

let taskList;
let acceptBtnBtn;
let titleTd;
let detailTd;
let deadlineTd;

//Popup
let popup;
let titleInput;
let detailInput;
let deadlineInput;
let target;
let infoMessageElement;
let popupInfo;


taskList = document.querySelector('#task-list');
popup = document.querySelector('.popup')
acceptBtnBtn = document.querySelector('.accept');
infoMessageElement = document.querySelector('.info-message')
popupInfo = document.querySelector('.popup-info');


const updateTaskList = (tasks, includeEditClass) => {
    taskList.innerHTML = '';

    createTableHead(taskList);
    createTableBody(taskList, tasks, includeEditClass);
};

const createTableHead = taskList => {
    const tr = document.createElement('tr');
    const title = document.createElement('th');
    title.textContent = 'Title';
    const details = document.createElement('th');
    details.textContent = 'Details';
    const tools = document.createElement('th');
    tools.textContent = 'Tools[quantity]';
    const deadline = document.createElement('th');
    deadline.textContent = 'Deadline';
    const completed = document.createElement('th');
    completed.textContent = 'Completed';


    tr.append(title, details,tools, deadline, completed);
    taskList.append(tr);
};

const createTableBody = (taskList, tasks, includeEditClass) => {
    tasks.forEach(task => {
        const tr = document.createElement('tr');

        const title = document.createElement('td');
        title.textContent = task.title;
        if (includeEditClass) {
            title.classList.add('edit', 'title');
            title.dataset.id = task.id;
        }

        const details = document.createElement('td');
        details.textContent = task.details;
        if (includeEditClass) {
            details.classList.add('edit', 'detail');
            details.dataset.id = task.id;
        } else {
            details.classList.add('td-detail');
        }

        const tools = document.createElement('td');
        const ul = document.createElement('ul');
        console.log(task.tools)

        const decodeTools = JSON.parse(task.tools);
        console.log(decodeTools)
        console.log(decodeTools !== [], decodeTools.length)
        if (decodeTools.length > 0) {
            console.log('halo')
            decodeTools.forEach(tool => {

                console.log(tool)
                const li = document.createElement('li')
                li.textContent = tool.name + '[' + tool.quantity + ']'
                ul.appendChild(li);
            });
            tools.appendChild(ul)
        }


        const deadline = document.createElement('td');
        deadline.textContent = task.deadline.replace(/-/g, '.');
        if (includeEditClass) {
            deadline.classList.add('edit', 'deadline');
            deadline.dataset.id = task.id;
        }

        const completed = document.createElement('td');
        completed.innerHTML = task.completed ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
        if (includeEditClass) {completed.classList.add('complete');}
        completed.classList.add(task.completed ? 'green-completed' : 'red-completed');
        completed.dataset.id = task.id;

        tr.append(title, details,tools, deadline, completed);
        taskList.append(tr);
    });
};


const checkClick = e => {
    target = e.target
    if (e.target.matches('.complete')) {
        let taskId = e.target.dataset.id;
        e.target.classList.toggle('green-completed');
        e.target.classList.toggle('red-completed');
        e.target.innerHTML = e.target.classList.contains('green-completed') ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
        let isCompleted = e.target.classList.contains('green-completed');
        saveToDB(taskId, isCompleted,)
    } else if (e.target.classList.contains('td-detail')) {
        popupShow(e.target)
    } else if (e.target.classList.contains('edit')) {
        popupEdit(e.target)
    }
};

const saveToDB = (taskId, isCompleted, title, details, deadline) => {
    fetch(`${URL}/tasks/${taskId}`, {
        method: 'PUT',
        body: JSON.stringify({
            completed: isCompleted,
            title: title,
            details: details,
            deadline: deadline
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('An error occurred during the request')
            }
        })
        .then(data => {
            console.log('Changes saved in the database:', data);
            showMessage('Task updated successfully', 'success')
            // sendEmail(data.email, "Task manager", `Task which title is: ${data.title} is completed: ${data.isCompleted}`)
        })
        .catch(error => {
            console.error('An error occurred while saving changes to the database:', error);
            showMessage('Something went wrong', 'error')
        });
};

const popupShow = e => {
    const taskDetails = document.querySelector('.expanded-info-text')
    taskDetails.textContent = e.textContent;
    popup.style.display = 'flex';
};

const popupEdit = e => {

    let row = e.closest('tr');
    titleTd = row.querySelector('.edit.title');
    detailTd = row.querySelector('.edit.detail');
    deadlineTd = row.querySelector('.edit.deadline');

    titleInput = document.querySelector('.popup-title-input')
    detailInput = document.querySelector('.popup-detail-input')
    deadlineInput = document.querySelector('.popup-deadline-input')

    titleInput.value = titleTd.textContent
    detailInput.value = detailTd.textContent
    deadlineInput.value = deadlineTd.textContent

    popup.style.display = 'flex';
}

const close = () => {
    popup.style.display = 'none';
    popupInfo.textContent = '';
}

const showMessage = (message, info) => {
    infoMessageElement.textContent = message
    switch (info) {
        case 'success':
            infoMessageElement.classList.add('alert', 'alert-success')
            break
        case 'error':
            infoMessageElement.classList.add('alert', 'alert-danger')
            break
    }
}

function sendEmail(to, subject, body) {
    const mailtoLink = "mailto:" + encodeURIComponent(to) +
        "?subject=" + encodeURIComponent(subject) +
        "&body=" + encodeURIComponent(body);

    const link = document.createElement("a");
    link.href = mailtoLink;
    link.target = "_blank";
    link.click();
}

export {
    URL, taskList, titleInput, detailInput, deadlineInput, titleTd,
    detailTd, deadlineTd, target,
    updateTaskList, checkClick, close, saveToDB, showMessage
}


