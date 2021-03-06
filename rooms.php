<?php
  include 'db.php';
  include 'saveRoomNew.php';
   $connection = db_connect();

  $buildingsStatement = "SELECT building, floors
            FROM buildings";

  $buildingsInfo = get($connection, $buildingsStatement);

  $buildings = "";
  for($i = 0; $i < sizeof($buildingsInfo); ++$i) {
    $buildingName = $buildingsInfo[$i]['building'];
    $buildings .= $buildingName . ':';
      $floors = explode(',', $buildingsInfo[$i]['floors']);
      foreach($floors as $f) {
        $buildings .= $f . '-' . getFloors($connection, $buildingName, $f). '*';
      }
      $buildings .= '_';
  }

  $rooms = "";
  for($i = 0; $i < sizeof($buildingsInfo); ++$i) {
    $buildingName = $buildingsInfo[$i]['building'];
    $rooms .= $buildingName . ':';
      $floors = explode(',', $buildingsInfo[$i]['floors']);
      foreach($floors as $f) {
        $rooms .= $f . '-';
        $roomsInfo = getRooms($connection, $buildingName, $f);
        for($r = 0; $r < sizeof($roomsInfo); ++$r) {
          $rooms .= $roomsInfo[$r]['room'] . '=' . $roomsInfo[$r]['type'] . ' ' . $roomsInfo[$r]['seatsCnt'] . ' ' .$roomsInfo[$r]['computers'] . ' ' . $roomsInfo[$r]['whiteBoard'] . '?';
        }
        $rooms .= '*';
      }
      $rooms .= '_';
  }

  //$roomsTaken =  getRoomsTakenDate($connection, date("Y-m-d h:i:s"));
  $statement = "SELECT date, building, room, floor, title, type, lecturer, speciality, groupAdm, year, duration
      FROM roomTaken";
  $roomsTaken = get($connection, $statement);

 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type = "text/javascript">

  let building;
  let floorNum;
  let roomTypes = {};
  let allRooms = getMapRooms();
  let allRoomData = getRoomData();
  let availableData = getAvailability();

  function sleep(ms) {
  return new Promise(
    resolve => setTimeout(resolve, ms)
  );
}
  function createFloor(floors, roomTypes) {
      let map = document.getElementById('map');
      let div = document.createElement('div');
      div.className = 'rooms';
      div.id = `${building}-${floorNum}`;
      for (let i = 0; i < floors.length; ++i) {
          for (let j = 0; j < floors[i].length; ++j) {
              let child = document.createElement('div');
              let room = floors[i][j];
              if (room == 'x' || room == 's' || room == 't') {
                if (room == 'x') {
                    child.className = 'not-available';
                }
                else if (room == 's') {
                    child.className = 'stairs';
                }
                else if (room == 't') {
                    child.className = 'wc';
                    child.innerHTML = 'WC';
                }
              }
              else {
                  let roomType = roomTypes[room].type;
                  child.innerHTML = room;
                  if (roomType == '1' && roomTypes[room].computers == '??') {
                      child.className = 'seminar-room';
                  }
                  else if (roomType == '3') {
                      child.className = 'lecture-room';
                  }
                  else if (roomType == '2') {
                      child.className = 'big-computer-room';
                  }
                  else if (roomType == '1') {
                      child.className = 'small-computer-room';
                  }
              }
              div.appendChild(child);
          }
          let breakFlex = document.createElement('div');
          breakFlex.className = 'breakFlex';
          div.appendChild(breakFlex);
      }
      //div.classList.add('hidden');
      map.parentNode.insertBefore(div, map.nextSibling);
  }

  function colorFree(floorName, roomTypes, availableRooms) {
      curFloor = document.getElementById(floorName).children;
      for (let i = 0; i < curFloor.length; ++i) {
          let room = curFloor[i].innerHTML;
          if (room == '' || room == 'WC') {
              continue;
          }
          if (isTaken(availableRooms, room)) {
            curFloor[i].classList.remove('empty-room');
            curFloor[i].classList.add('taken-room');
          }
          else {
            curFloor[i].classList.remove('taken-room');
            curFloor[i].classList.add('empty-room');
          }
      }
  }

  function callSelectFloor(event) {
    floorNum = parseInt(this.innerHTML.substring(5,6));
    selectFloor(null);
    event.target.classList.add('selectedButton');
  }

  function selectFloor(date) {
    let mapRooms = allRooms[building][floorNum];
    let roomData = allRoomData[building][floorNum];

    if (date == null) {
      let hours =  document.getElementById('timeInput').value;
      let day = document.getElementById('dateInput').value;
      date =  day + ' ' + hours.substring(0, 3) + '00:00';
    }

    if(availableData[date] == null || availableData[date][building] == null || availableData[date][building][floorNum] == null) {
      availableRooms = null;
    } else {
      availableRooms = availableData[date][building][floorNum];
    }

    roomTypes = {};
    for (key in roomData) {
      let attributes = roomData[key].split(' ');
      roomTypes[key] = {
        type: attributes[0],
        seatsCnt: attributes[1],
        computers: attributes[2],
        whiteBoard: attributes[3]
      }
    }
    document.getElementById('map').nextSibling.remove();
    createFloor( mapRooms.split('|').map(row => row.split(' ')), roomTypes);
    colorFree(`${building}-${floorNum}`, roomTypes, availableRooms);

    let rooms = document.getElementById(`${building}-${floorNum}`).childNodes;
    rooms.forEach(child => {child.addEventListener('click', popRoom);});

    let floors = document.getElementsByClassName('sideNavButton');
    Array.prototype.forEach.call(floors, floor => {
      floor.classList.remove('selectedButton');
    });
  }

  function popRoom(event) {
      let elem = event.target;
      let popUpRoom = document.getElementById('pop-up-room');
      let title = document.getElementById('pop-up-room-img-title');
      let text = document.getElementById('pop-up-room-img-side-text');

      let roomNum = elem.innerHTML;
      let roomObj = roomTypes[roomNum];

      let popUpRoomImg = document.getElementById('pop-up-room-img');

      let roomType = '????????????????????';
      if (elem.classList.contains('big-computer-room')) {
          roomType = '???????????? ????????????????????';
          popUpRoomImg.style.backgroundImage = 'url("./img/big-computer-room.png")';
      }
      else if (elem.classList.contains('small-computer-room')) {
          roomType = '?????????? ????????????????????';
          popUpRoomImg.style.backgroundImage = 'url("./img/small-computer-room.png")';
      }
      else if (elem.classList.contains('lecture-room')) {
          roomType = '?????????????????? ????????';
          popUpRoomImg.style.backgroundImage = 'url("./img/lecture-room.png")';
      }
      else if (elem.classList.contains('seminar-room')) {
          roomType = '?????????????????? ????????';
          popUpRoomImg.style.backgroundImage = 'url("./img/seminar-room.png")';
      }

      let whiteBoard = (roomObj.whiteBoard == '??') ? '????' : '????';

      title.innerHTML = `???????? ${elem.innerHTML}`;

      let hours =  document.getElementById('timeInput').value;
      let day = document.getElementById('dateInput').value;
      date =  day + ' ' + hours.substring(0, 3) + '00:00';

      if(availableData[date] == null ||  availableData[date][building] == null || availableData[date][building][floorNum] == null || availableData[date][building][floorNum][roomNum] == null) {
        text.innerHTML = `<pre>??????:\n${roomType}\n\n???????? ??????????: ${roomObj.seatsCnt}\n\n???????? ??????????: ${whiteBoard}</pre>`
      } else {
        let temp = availableData[date][building][floorNum][roomNum];
        text.innerHTML = `<pre>??????:\n${roomType}\n\n???????? ??????????: ${roomObj.seatsCnt}\n\n???????? ??????????: ${whiteBoard}\n\n??????????\n${temp.title}-${temp.type} ${temp.lecturer}\n${temp.speciality} ${temp.groupAdm} ??????????</pre>`
      }
      popUpRoom.classList.remove('hidden');
  }

  function addZero(attr) {
    return (attr < 10) ? `0${attr.toString()}` : attr.toString();
  }

  function setTimeFieldsToToday() {
    let dateRaw = new Date();

    //set date
    let dateInput = document.getElementById('dateInput');
    let year = dateRaw.getFullYear().toString();
    let month = addZero(dateRaw.getMonth() + 1);
    let day = addZero(dateRaw.getDate());
    let dateFormatted = `${year}-${month}-${day}`;
    dateInput.value = dateFormatted;

    //set time
    let timeInput = document.getElementById('timeInput');
    let hours = addZero(dateRaw.getHours());

    let minutes = addZero(dateRaw.getMinutes());
    let seconds = addZero(dateRaw.getSeconds());
    let timeFormatted = `${hours}:${minutes}:${seconds}`;
    timeInput.value = timeFormatted;
  }

  function closeFormFunc() {
  
      formContainer = document.getElementById('formContainer');
      formContainer.classList.add('hidden');
      document.querySelectorAll('#saveRoom > div > input').forEach(elem => {
        elem.value = '';
      })}

  window.addEventListener('load', (event) => {
    setTimeFieldsToToday();

    let buildingButtons = document.querySelectorAll('.topNavButton');
    buildingButtons.forEach(button => button.addEventListener('click', function(event) {
      let elem = event.target;
      buildingButtons.forEach(button => button.classList.remove('selectedButton'));
      elem.classList.add('selectedButton');
      floorNum = 2;
      building = elem.id.split('-')[0];
      selectFloor(null);

      sideNavBars = document.querySelectorAll(".sideNavBar");
      sideNavBars.forEach(elem => {
        if (!elem.classList.contains('hidden'))
          elem.classList.add('hidden')
        });
      curSideBar = document.getElementById(`${building}-nav`);
      curSideBar.classList.remove('hidden');
      let buttons = document.querySelectorAll(`#${building}-nav > div > .sideNavButton`);
      buttons.forEach(button => {
        if (button.innerHTML.split(' ')[1] == 2) {
          button.classList.add('selectedButton');
        }
      })
    }));

    building = '??????';
    floorNum = 3;
    let mapRooms = allRooms[building][floorNum];
    let roomData = allRoomData[building][floorNum];
    let hours =  document.getElementById('timeInput').value;
    let day = document.getElementById('dateInput').value;
    date =  day + ' ' + hours.substring(0, 3) + '00:00';

    if(availableData[date] == null || availableData[date][building] == null || availableData[date][building][floorNum] == null) {
      availableRooms = null;
    } else {
      availableRooms = availableData[date][building][floorNum];
    }
    roomTypes = {};
    for (key in roomData) {
      let attributes = roomData[key].split(' ');
      roomTypes[key] = {
        type: attributes[0],
        seatsCnt: attributes[1],
        computers: attributes[2],
        whiteBoard: attributes[3]
      }
    }
    createFloor(mapRooms.split('|').map(row => row.split(' ')), roomTypes);
    colorFree(`${building}-${floorNum}`, roomTypes, availableRooms);

    let list = document.getElementsByClassName('sideNavButton');
    Array.from(list).forEach(item => {
      item.addEventListener("click", callSelectFloor);
    });

    let x = document.getElementById('x');
    x.addEventListener('click', closeCloseUp);

    let closeForm = document.getElementById('closeForm');
    closeForm.addEventListener('click', closeFormFunc);

    let openForm = document.getElementById('openForm');
    openForm.addEventListener('click', function () {
      formContainer = document.getElementById('formContainer');
      let date = document.getElementById('dateInput').value;
      let time = document.getElementById('timeInput').value;
      document.getElementById('saveDate').value = date;
      document.getElementById('saveTime').value = time;
      document.getElementById('building').value = building;
      document.getElementById('floor').value = floorNum;

      formContainer.classList.remove('hidden');
    });

    let saveFromRoom = document.getElementById('saveFromRoom');
    saveFromRoom.addEventListener('click', function (event) {
      document.getElementById('pop-up-room').classList.add('hidden');
      let formContainer = document.getElementById('formContainer');
      let date = document.getElementById('dateInput').value;
      let time = document.getElementById('timeInput').value;
      document.getElementById('saveDate').value = date;
      document.getElementById('saveTime').value = time;
      document.getElementById('building').value = building;
      document.getElementById('floor').value = floorNum;  
      let roomNum = document.getElementById('pop-up-room-img-title').innerHTML.split(' ')[1];
      document.getElementById('room').value = roomNum;      

      formContainer.classList.remove('hidden');
    });

    let formContainer = document.getElementById('formContainer');
      let warning = document.createElement('div');
      warning.id = 'warning';
      warning.innerHTML = "???? ???????? ???? ???????? ???????????????? ???????? ?? ???????????????????? ??????????!";
      warning.classList.add('warning');
      let ok = document.createElement('div');
      ok.id = 'ok';
      ok.innerHTML = 'ok';
      warning.appendChild(ok);
      ok.addEventListener('click', function() {
        document.getElementById('warning').classList.add('hidden');
      });
      warning.classList.add('hidden');
      formContainer.appendChild(warning);

    let saveForm = document.getElementById('saveForm');
    saveForm.addEventListener('click', async function (event) {
      let groupId = document.getElementById('group').value;
      let subjectId = document.getElementById('subject').value;

      let building = document.getElementById('building').value;
      let lecturerName = document.getElementById('lecturerName').value;
      let floor = document.getElementById('floor').value;
      let room = document.getElementById('room').value;
      let temphour = document.getElementById('saveTime').value.split(':')[0];
      let tempday = document.getElementById('saveDate').value;
      let date = tempday+ ' ' + temphour + ':00:00';
      
      let subjectTitle = "";
      let courseType = "";
      let duration = "";

      let speciality ="";
      let year = "";
      let groupAdm = "";

        await $.post('getGroupData.php', {'gr_id' : groupId}, function(data){
            var jsonData = JSON.parse(data); 
            groupAdm=jsonData[0].groupAdm;
            speciality =decodeURIComponent(jsonData[0].speciality);
            year=jsonData[0].year;
        });
        await $.post('getSubjectData.php', {'sub_id' : subjectId}, function(data){
            var jsonData = JSON.parse(data);       
            subjectTitle = decodeURIComponent(jsonData[0].subject_name);
            courseType = jsonData[0].type;
            duration = jsonData[0].duration;
        });
        
      let rooms = document.getElementById(`${building}-${floorNum}`).childNodes;
      let free = true;
      for (elem of rooms){
        console.log(room)
        if (elem.innerHTML == room && elem.classList.contains('taken-room')) {
          free = false;
          break;
        }
      }
      

      if (!free || building == '' || floor == '' || room == '' || temphour == '' || date == '' || groupId == '' || subjectId == '' ) {
          document.getElementById('warning').classList.remove('hidden');
        }
      else {
        if(availableData[date]== null) {
            availableData[date] = {};
          }
          if(availableData[date][building] == null) {
            availableData[date][building] = {};
          }
          if(availableData[date][building][floor] ==null) {
            availableData[date][building][floor] = {};
          }
          if(availableData[date][building][floor][room] == null){
            availableData[date][building][floor][room] = {};
          }

          availableData[date][building][floor][room] = {
            'duration': duration,
            'groupAdm': groupAdm,
            'lecturer': lecturerName,
            'speciality': speciality,
            'title': subjectTitle,
            'type': courseType,
            'year': year
          };

          hours = parseInt(temphour);
          for (let d = 1; d < duration; ++d) {
            hours = hours >= 24 ? 0 : hours + 1;
            let temph = tempday + ' ' + addZero(hours) + ':00:00';
            if(availableData[temph] == null) {
              availableData[temph] = {};
            }
            if(availableData[temph][building] == null) {
              availableData[temph][building] = {};
            }
            if(availableData[temph][building][floor] == null) {
              availableData[temph][building][floor] = {};
            }
            availableData[temph][building][floor][room] = availableData[date][building][floor][room];
            
          }

          selectFloor(date);
          let buttons = document.querySelectorAll(`#${building}-nav > div > .sideNavButton`);
          buttons.forEach(button => {
            if (button.innerHTML.split(' ')[1] == floorNum) {
              button.classList.add('selectedButton');
            }
          })
          closeFormFunc();
          debugger;
          $.post("saveRoomNew.php",{b:building,f:floor,r:room,st:subjectTitle,
          ty:courseType,l:lecturerName,s:speciality,gr:groupAdm,y:year,d:date,dur:duration,s_id:subjectId,g_id:groupId},function(response){
             console.log(response);
         });
          
      }
  })

    


    let checkAvailability = document.getElementById('checkAvailability');
    checkAvailability.addEventListener('click', function () {
      let hours =  document.getElementById('timeInput').value;
      let day = document.getElementById('dateInput').value;
      date =  day + ' ' + hours.substring(0, 3) + '00:00';
      selectFloor(date);
      let buttons = document.querySelectorAll(`#${building}-nav > div > .sideNavButton`);
      buttons.forEach(button => {
        if (button.innerHTML.split(' ')[1] == floorNum) {
          button.classList.add('selectedButton');
        }
      })
    });


    let rooms = document.getElementById(`${building}-${floorNum}`).childNodes;
    rooms.forEach(child => {child.addEventListener('click', popRoom);});
  });




  let clickHandler = function (event) {
      let elem = event.target;
  }
  let closeCloseUp = function (event) {
      let elem = event.target;
      let fig = elem.parentElement;
      fig.classList.add("hidden");
  }

  function getMapRooms () {
    let phpData = "<?php echo $buildings; ?>";
    mapRooms = phpData.split('_').filter(y=>y).map(x => x.split(':').map(x=>x.split('*').filter(y=>y).map(x=>x.split('-'))));
    mapObj = {};
    for (let i = 0; i < mapRooms.length; ++i) {
      mapObj[mapRooms[i][0]] = {};
      for (let j = 0; j < mapRooms[i][1].length; ++j) {
        mapObj[mapRooms[i][0]][mapRooms[i][1][j][0]] = mapRooms[i][1][j][1]
      }
    }
    return mapObj;
  }

  function getRoomData () {
    let phpData = "<?php echo $rooms; ?>";
    roomData = phpData.split('_').filter(y=>y).map(x => x.split(':').map(x=>x.split('*').filter(y=>y).map(x=>x.split('-').map(x=>x.split('?').filter(y=>y).map(x=>x.split('='))))));
    roomObj = {};
    for (let i = 0; i < roomData.length; ++i) {
      roomObj[roomData[i][0]] = {};
      for (let j = 0; j < roomData[i][1].length; ++j) {
        roomObj[roomData[i][0]][roomData[i][1][j][0]] = {};
        for (let k = 0; k < roomData[i][1][j][1].length; ++k) {
          roomObj[roomData[i][0]][roomData[i][1][j][0]][roomData[i][1][j][1][k][0]] = roomData[i][1][j][1][k][1];
        }
      }
    }
    return roomObj;
  }

  function getAvailability () {
    let availabilityData = <?php echo json_encode($roomsTaken); ?>;
    availabilityObj = {};
    for (let i = 0; i < availabilityData.length; ++i) {
      if(availabilityObj[availabilityData[i]['date']] == null) {
        availabilityObj[availabilityData[i]['date']] = {};
      }
      if(availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']] == null) {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']] = {};
      }
      if(availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']] == null) {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']] = {};
      }

      const notAllowed = ['building', 'floor', 'room', 'date'];
      if (  availabilityObj[availabilityData[i]['date']] == null || availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']] == null || availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']] == null
      || availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']] == null) {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']]
          = Object.keys(availabilityData[i]).filter(key => !notAllowed.includes(key))
                  .reduce((result, key) => {
                      result[key] = availabilityData[i][key];
                      return result;
                  }, {});
      } else {
        availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']]['groupAdm'] +=  ',' + availabilityData[i]['groupAdm']
      }
      let day = availabilityData[i]['date'].split(' ')[0];
      let hours = parseInt(availabilityData[i]['date'].split(' ')[1].split(':')[0]);
      for (let d = 1; d < availabilityData[i]['duration']; ++d) {
        hours = hours >= 24 ? 00 : hours + 1;
        let temp = day + ' ' + addZero(hours) + ':00:00';
        if(availabilityObj[temp] == null) {
          availabilityObj[temp] = {};
        }
        if(availabilityObj[temp][availabilityData[i]['building']] == null) {
          availabilityObj[temp][availabilityData[i]['building']] = {};
        }
        if(availabilityObj[temp][availabilityData[i]['building']][availabilityData[i]['floor']] == null) {
          availabilityObj[temp][availabilityData[i]['building']][availabilityData[i]['floor']] = {};
        }
        availabilityObj[temp][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']] = availabilityObj[availabilityData[i]['date']][availabilityData[i]['building']][availabilityData[i]['floor']][availabilityData[i]['room']];
      
      }
    }
    return availabilityObj;
  }

  function isTaken (availableRooms, room) {
    if (availableRooms == null || availableRooms[room] == null) {
      return false;
    }
    return true;
  }



</script>