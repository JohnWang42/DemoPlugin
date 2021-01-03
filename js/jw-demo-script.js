//populate table with initial user data
document.addEventListener('DOMContentLoaded', function () {
    makeTable(jwdemo.users);
});

/**
 * Takes in user JSON data and formats an HTML table and associated click events
 * @param data
 */
function makeTable(data)
{
    let tableData = "<tr><th>ID</th><th>NAME</th><th>USERNAME</th></tr>";
    data.forEach((user) => {
        tableData += `<tr id="jw-user-${user.id}" data-userid=${user.id}>` +
            `<td><a onclick="makeDetailRow(${user.id})">${user.id}</a></td>` +
            `<td><a onclick="makeDetailRow(${user.id})">${user.name}</a></td>` +
            `<td><a onclick="makeDetailRow(${user.id})">${user.username}</a></td>` +
            "</tr>";
    });
    let table = document.getElementById('jw-demo-users');
    table.innerHTML = tableData;
}

/**
 * Given user id, append a row after that user's basic info with more details
 * @param id
 */
function makeDetailRow(id)
{
    lockTable();
    closeDetails();
    const userRow = document.getElementById(`jw-user-${id}`);
    getUser(id)
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                let user = response.data.user[0];
                userRow.insertAdjacentHTML('afterend', '<tr class="jw-user-details">' +
                    '<td colspan="3"><a class="close-btn" onclick="closeDetails()">X</a><div class="td-wrapper">' +
                    '<div class="contact"><h6>Contact Info</h6>' +
                    `<strong>Email: </strong>${user.email}<br>` +
                    `<strong>Phone: </strong>${user.phone}<br>` +
                    `<strong>Website: </strong>${user.website}<br></div>` +
                    '<div class="address"><h6>Address</h6>' +
                    `${user.address.street}<br>` +
                    `${user.address.suite}${user.address.suite ? '<br>' : ''}` +
                    `${user.address.city}, ${user.address.zipcode}<br></div>` +
                    '<div class="company"><h6>Company</h6>' +
                    `${user.company.name}<br>` +
                    `${user.company.catchPhrase}<br></div>` +
                    '</div></td></tr>');
            } else {
                showMsg('Unable to retrieve user data', 'error');
            }
            unlockTable();
        })
        .catch((error) => {
            console.error('Error:', error);
            showMsg('Unable to retrieve user data', 'error');
            unlockTable();
        });
}

/**
 * Displays a message above the table to inform the user of various statuses
 * @param {string} msg Message to display
 * @param {string} status Possible entries (info, success, error), defaults to info
 */
function showMsg(msg, status="info")
{
    const box = document.getElementById('jw-msg-box');
    box.innerText = msg;
    box.classList.remove('hidden', 'info', 'success', 'error');
    box.classList.add(status);

    setInterval(() => {
        document.getElementById('jw-msg-box').classList.add('hidden');
    }, 5000);
}

/**
 * Gets JSON from plugin AJAX hooks
 * @return {Promise}
 */
async function getUsers()
{
    const params = new URLSearchParams({
        action: 'jwGetUsers',
        nonce: jwdemo._nonce
    });

    return await fetch(jwdemo.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    });
}

/**
 * Gets specific user info from plugin AJAX hook
 * @param {int} id The ID of the user you wish to get details for
 * @return {Promise}
 */
async function getUser(id)
{
    const params = new URLSearchParams({
        action: 'jwGetUser',
        jw_userid: id,
        nonce: jwdemo._nonce
    });

    return await fetch(jwdemo.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: params.toString()
    });
}

/**
 * Remove user details row
 */
function closeDetails()
{
    const detailRows = document.querySelectorAll('.jw-user-details');
    detailRows.forEach((el) => {
        el.remove();
    });
}

function lockTable() {
    document.getElementById('jw-demo-modal').classList.remove('hidden');
}

function unlockTable() {
    document.getElementById('jw-demo-modal').classList.add('hidden');
}