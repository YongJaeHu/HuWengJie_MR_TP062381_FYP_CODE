let body = document.body;
let profile = document.querySelector('.header .upper .profile');
let notification = document.querySelector('.header .upper .notification');
let order = document.querySelector('.header .upper .order');
let side = document.querySelector('.side');

document.querySelector('#user_btn').onclick = () =>{
   profile.classList.toggle('active');
}

document.querySelector('#notification_btn').onclick = () =>{
  notification.classList.toggle('active');
}

document.querySelector('#order_btn').onclick = () =>{
  order.classList.toggle('active');
}

document.querySelector('#menu_btn').onclick = () =>{
   side.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelector('.side .close_side').onclick = () => {
   side.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelectorAll('.posts-content').forEach(content => {
  if(content.innerHTML.length > 120) content.innerHTML = content.innerHTML.slice(0, 100);
})

document.querySelectorAll('.description').forEach(content => {
  if(content.innerHTML.length > 120) content.innerHTML = content.innerHTML.slice(0, 100);
});

var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}

$('.sidenav').click(function(){
  $(this).addClass("active").siblings().removeClass("active");
});





