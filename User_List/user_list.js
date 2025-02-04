        // Get the pop_up
        var pop_up = document.getElementById("pop-up");

        // Get the button that opens the pop_up
        var button = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the pop_up 
        function clicked_pen() {
            pop_up.style.display = "block";
        }

        button.onclick = function(){
            pop_up.style.display = "none";
        }

        // When the user clicks anywhere outside of the pop_up, close it
        window.onclick = function(event) {
            if (event.target == pop_up) {
                pop_up.style.display = "none";
            }
        }

        var last_responseText;
        function showUsers(str) {
            //When called by setInterval
            if (str == null){
                str = document.getElementById('search-input').value
            }
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                if (this.responseText != last_responseText)
                    document.getElementById("user-list").innerHTML = last_responseText = this.responseText;
            }
            xhttp.open("GET", "get_userlist.php?search="+str);
            xhttp.send();
        }

        //Auto userlist refresh
        setInterval(showUsers, 500);