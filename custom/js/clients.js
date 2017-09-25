$(document).ready(function() {
  var action = "create";
  updateTableList();
  $("#createClientBtn").click(function(e) {
    e.preventDefault();
    // actions[action]();
  });

  $("#addClientModalBtn").on('click', function() {
    action = "create";
  });

  $("#manageClientTable").on('click', ".editBtn", function() {
    action = "update";
    actions.selectedId = $(this).data('id');
    $("#clientName").val($(this).data('name'));
    $("#clientAddress").val($(this).data('address'));
    $("#clientContact").val($(this).data('contact'));
  });
});

var actions = {
  create: createClient,
  update: updateClient,
  selectedId: -1,
};

function createClient() {
  var name = $("#clientName").val();
  var address = $("#clientAddress").val();
  var contact = $("#clientContact").val();
  $.ajax({
    url: 'php_action/createClient.php',
    type: 'post',
    data: {
      name: name,
      contact: contact,
      address: address,
    },
    dataType: 'json',
    success:function(response) {
      showSuccessMessage(response.messages, response.success);

      if(response.success) updateTableList();

      $("#addClientModel").modal("hide");
    },
    error: function(err) {
      console.log(err);
    }
  });
}

function updateClient() {
  var name = $("#clientName").val();
  var address = $("#clientAddress").val();
  var contact = $("#clientContact").val();
  $.ajax({
    url: 'php_action/editClient.php',
    type: 'post',
    data: {
      id: actions.selectedId,
      name: name,
      contact: contact,
      address: address,
    },
    dataType: 'json',
    success:function(response) {
      showSuccessMessage(response.messages, response.success);

      if(response.success) updateTableList();

      $("#addClientModel").modal('hide');
    },
    error: function(err) {
      console.log(err);
    }
  });
}


function updateTableList() {
  $.ajax({
    url: 'php_action/refreshClientList.php',
    success:function(clients) {
      var rows = JSON.parse(clients).sort(function(a, b){
          return a.name > b.name
      }).map(function(client) {
        var row = '<td>' + client.name + '</td>';
        row += '<td>' + client.address + '</td>';
        row += '<td>' + client.contact + '</td>';
        row += '<td><span class="label label-success">' + (client.status ? 'Available' : 'Not Available') + '</span></td>';
        row += '<td><button class="btn btn-default button1 editBtn" data-toggle="modal" data-target="#addClientModel" data-name="'+client.name+'" data-address="' + client.address + '" data-id="' + client.client_id + '" data-contact="' + client.contact + '"> <i class="glyphicon glyphicon-edit"></i> Edit </button></td>'
        return "<tr>" + row + "</tr>";
      }).join('');

      $("#manageClientTable tbody").empty().append(rows);
    }
  });
}

function showSuccessMessage(messages, success) {
  $("#success-messages").html('<div class="alert alert-' + (success ? 'success' : 'danger') + '">'+
    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ messages +
  '</div>');

  // remove the mesages
  $(".alert-success").delay(500).show(10, function() {
    $(this).delay(3000).hide(10, function() {
      $(this).remove();
    });
  }); // /.alert
}