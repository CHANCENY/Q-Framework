<?php @session_start();
$users = \Datainterface\Selection::selectAll('users');
?>

<div id="alertbox"></div>
<table class="table table-hover">

    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Firstname</th>
        <th scope="col">Lastname</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Role</th>
        <th scope="col">Blocked</th>
        <th scope="col">Verified</th>
    </tr>
    </thead>

    <!--body-->
    <?php if(!empty($users)): ?>
    <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
        <th scope="row"><?php echo $user['uid']; ?></th>
        <td><?php echo $user['firstname']; ?></td>
        <td><?php echo $user['lastname']; ?></td>
        <td><?php echo $user['mail']; ?></td>
        <td><?php echo $user['phone']; ?></td>
        <td>
            <?php

             $selectAdmin = false;
             if($user['role'] === "Admin"){
                 $selectAdmin = true;
             }
            ?>
            <select name="role" id="role<?php echo $user['uid']; ?>">
                <option value="admin-<?php echo $user['uid']; ?>" <?php echo $selectAdmin === true ? 'selected' : ""; ?> >admin</option>
                <option value="user-<?php echo $user['uid']; ?>" <?php echo $selectAdmin === false ? 'selected' : ""; ?> >user</option>
            </select>
        </td>
        <td>
            <?php
              $selectBlocked = false;
              if(!empty($user['blocked'])){
                 $selectBlocked = true;
              }
            ?>
            <select name="block" id="block<?php echo $user['uid']; ?>">
                <option value="block-<?php echo $user['uid']; ?>"  <?php echo $selectBlocked === true ? 'selected' : ""; ?>>unblock</option>
                <option value="unblock-<?php echo $user['uid']; ?>"  <?php echo $selectBlocked === false ? 'selected' : ""; ?>>block</option>
            </select>
        </td>
        <td>
            <?php
               $selectVerified = false;
               if(!empty($user['verified'])){
                   $selectVerified = true;
               }
            ?>
            <select name="verified" id="verified<?php echo $user['uid']; ?>">
                <option value="verified-<?php echo $user['uid']; ?>" <?php echo $selectVerified === true ? 'selected' : ""; ?>>un verify</option>
                <option value="unverified-<?php echo $user['uid']; ?>" <?php echo $selectVerified === false ? 'selected' : ""; ?>>verify</option>
            </select>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    <?php endif; ?>
</table>
<div>
    <script type="application/javascript">

        const requestSender = (params)=>{
            const url = window.location.protocol+'//'+window.location.hostname+'/users-commands?'+params;
            let xhr = new XMLHttpRequest();
            xhr.open('GET',url, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function (){
                if(this.status === 200){
                    const data = JSON.parse(this.responseText);
                    if(data.status === 200){
                        const box =document.getElementById('alertbox');
                        let div = document.createElement('div');
                        div.className = "alert alert-success";
                        div.id = "current-alert";
                        div.appendChild(document.createTextNode(data.msg));
                        box.appendChild(div);
                        setTimeout(()=>{
                            document.getElementById('current-alert').remove();
                        },4000);
                    }
                }
                if(this.status === 404){
                    const data = JSON.parse(this.responseText);
                    if(data.status === 404){
                        const box =document.getElementById('alertbox');
                        let div = document.createElement('div');
                        div.className = "alert alert-danger";
                        div.id = "current-alert";
                        div.appendChild(document.createTextNode(data.msg));
                        box.appendChild(div);
                        setTimeout(()=>{
                            document.getElementById('current-alert').remove();
                        },4000);
                    }
                }
            }
            xhr.onerror = function (){
                console.log(this.error);
            }
            xhr.send();
        }

        const handlerChangeBlock = (e) =>{
            const valueRecieved = e.target.value;
            if(valueRecieved !== ""){
                let list = valueRecieved.split('-');
                const command = list[0];
                const userId = list[1];
                const parameter = `command=${command}&userid=${userId}`;
                requestSender(parameter);
            }
        }

        const handlerChangeRole = (e)=>{
            const valueRecieved = e.target.value;
            if(valueRecieved !== ""){
                let list = valueRecieved.split('-');
                const command = list[0];
                const userId = list[1];
                const parameter = `command=${command}&userid=${userId}`;
                requestSender(parameter);
            }
        }

        const handlerChangeVerified =(e)=>{
            const valueRecieved = e.target.value;
            if(valueRecieved !== ""){
                let list = valueRecieved.split('-');
                const command = list[0];
                const userId = list[1];
                const parameter = `command=${command}&userid=${userId}`;
                requestSender(parameter);
            }
        }

        let selects = document.getElementsByName('block');
        for(let i = 0; i < selects.length; i++){
            selects[i].addEventListener('change', (e)=> {
                handlerChangeBlock(e)
            });
        }

        const selectrole = document.getElementsByName('role');
        for(let i = 0; i < selectrole.length; i++){
            selectrole[i].addEventListener('change', (e)=> {
                handlerChangeRole(e)
            });
        }

        const selectVerified =document.getElementsByName('verified');
        for(let i = 0; i < selectVerified.length; i++){
            selectVerified[i].addEventListener('change', (e)=> {
                handlerChangeVerified(e)
            });
        }

    </script>
</div>
