document.addEventListener('DOMContentLoaded', function () {
    console.log('Demo Script Loaded');
    console.log(jwdemo);
});

function getUsers()
{
    let params = new URLSearchParams({
        action: 'get_users',
        nonce: jwdemo.nonce
    });

    fetch(jwdemo.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
        .then(response => response.json())
        .then(data => {
            console.log("Success");
            console.log(data);
        })
        .catch((error) => {
            console.log("Error");
            console.log(error);
        });
}

function getUser(id)
{
    let params = new URLSearchParams({
        action: 'get_user',
        jw_userid: id,
        nonce: jwdemo.nonce
    });

    fetch(jwdemo.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
        .then(response => response.json())
        .then(data => {
            console.log("Success");
            console.log(data);
        })
        .catch((error) => {
            console.log("Error");
            console.log(error);
        });
}