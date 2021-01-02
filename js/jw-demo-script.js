document.addEventListener('DOMContentLoaded', function () {
    console.log('Demo Script Loaded');
    console.log(jwdemo);
});

function getUsers()
{
    let params = new URLSearchParams({
        action: 'jwGetUsers',
        nonce: jwdemo._nonce
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
        action: 'jwGetUser',
        jw_userid: id,
        nonce: jwdemo._nonce
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