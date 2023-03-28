const data = null;

const xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener('readystatechange', function () {
    if (this.readyState === this.DONE) {
        console.log(this.responseText);
    }
});

xhr.open('GET', 'https://api.travelperk.com/emissions/car?num_days=1&distance_per_day=300');
xhr.setRequestHeader('accept', 'application/json');
xhr.setRequestHeader('Api-Version', '1');
xhr.setRequestHeader('Authorization', '1NuGog.2ONgshDVKau96xqKmr4hkCCC76fzT9Qm');

xhr.send(data);