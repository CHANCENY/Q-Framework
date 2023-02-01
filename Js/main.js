
const  host = window.location.host;
const protocal = window.location.protocol;
const baseurl = `${protocal}//${host}/`;
const xhr = new XMLHttpRequest();
const params = "action=pagetitle";
xhr.open('GET', `${baseurl}Views/DefaultViews/dom-maniputor.view.php?${params}`, true);
xhr.onload = function (){
    if(this.status === 200){
        
        document.getElementById('titlepage').innerText = this.responseText;
    }
}
xhr.send();