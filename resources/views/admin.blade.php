<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">
<table class="table">
    <h1>Manage MySql appointments table</h1>
    <tr>
        <td>ID</td>
        <td>Event name</td>
        <td>Event start</td>
        <td>Event End</td>
        <td>User id</td>
        <td>Delete</td>
    </tr>
        @foreach($data as $d)
        <tr>
            <td>{{$d['id']}}</td>
            <td>{{$d['event_name']}}</td>
            <td>{{$d['event_start']}}</td>
            <td>{{$d['event_end']}}</td>
            <td>{{$d['user_id']}}</td>
            <td><button class="delete-button" data-id="{{$d['id']}}">Delete</button></td>
        </tr>
        @endforeach
</table>
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $('body').on('click', '.delete-button', function(){
        let id = $(this).data('id');

        $.ajax({
          url:"/delete",
          type:"POST",
          data:{
              id: id,
              type: 'delete'
          },
          success:function(data)
          {
              window.location.reload();
          }
        })
    })
</script>
