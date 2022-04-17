const form = document.querySelector(".typing_area"),
inputField = form.querySelector(".input_field"),
sendButton = form.querySelector("button");

form.onsubmit = (e)=>{
  e.preventDefault();
}

sendButton.onclick = ()=>{
  #create XML object
  let xhr = new XMLHttpRequest();
  xhr.open("POST","php/insert-message.php", true);
  xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
        //insert once message in db and empty the input field
            inputField.value ="";
        }
  }
  
  //send the data by ajax to php
  let data = new FormData(form);
  xhr.send(data);
}

setInterval()=>{
  let xhr = new XMLHttpRequest();
  xhr.open("GET","php/get_chat.php", true);
  xhr.onload =()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200) {
              let data = xhr.response;
              if(!searchBar.classList.contains("active")){
                userList.innerHTML = data;
              }
          }
      }
  }
  
  //send the data by ajax to php
  let data = new FormData(form);
  xhr.send(data);
},500);

