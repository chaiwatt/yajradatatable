@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Contact list
                    <a onclick="addForm()" class="btn btn-primary pull-right" style="margin-top: -8px;">Add Contact</a>
                </h4>
            </div>
            <div class="panel-body">
                <table id="contact-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

@include('layouts.form')

@endsection

@push('scripts')
<script type="text/javascript">
    var table = $('#contact-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('api.contact') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order:[
                    ['0','desc']
                ]
                });

    function addForm() {
      save_method = "add";
      $('input[name=_method]').val('POST'); 
      $('#modal-form form')[0].reset();
      $('.modal-title').text('Add Contact');
      $('#modal-form').modal('show');
    }

    function editForm(id) {
      save_method = 'edit';
      $('input[name=_method]').val('PATCH');
      $('#modal-form form')[0].reset();
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });        
      $.ajax({
        url:  '{{ route('contact.edit') }}',
        type: "post",
        data: {
            'id': id,
        },
        success: function(data) {         
          $('.modal-title').text('Edit Contact');
          $('#id').val(data.id);
          $('#name').val(data.name);
          $('#email').val(data.email);
          $('#modal-form').modal('show');
        }
      });
     }

    function deleteData(id){
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : '{{ route('contact.delete') }}',
                type: "post",
                data: {
                    'id': id,
                },
                success : function(data) {
                    table.ajax.reload();
                    swal({
                        title: 'Success!',
                        text: data.message,
                        type: 'success',
                        timer: '1500'
                    })
                },
                error : function () {
                    swal({
                        title: 'Oops...',
                        text: data.message,
                        type: 'error',
                        timer: '1500'
                    }).catch(function(timeout) { });
                }
            });
        });
      }



    $(function(){
          $('#modal-form form').on('submit', function (e) {
              if (!e.isDefaultPrevented()){
                  var id = $('#id').val();
                if(save_method == 'add'){
                    url = '{{ route('contact.create') }}' ;
                }else{
                    url = '{{ route('contact.editsave') }}' ;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                  $.ajax({
                      url : url,
                      type : "post",
                    data: {
                        'id': id,
                        'name': $('#name').val(),
                        'email': $('#email').val()
                    },
                      success : function(data) {
                           console.log(data);
                          $('#modal-form').modal('hide');
                          table.ajax.reload();
                          swal({
                              title: 'Success!',
                              text: data.message,
                              type: 'success',
                              timer: '1500'
                          }).catch(function(timeout) { });
                      },
                      error : function(data){
                          swal({
                              title: 'Oops...',
                              text: data.message,
                              type: 'error',
                              timer: '1500'
                          }).catch(function(timeout) { });
                      }
                  });
                  return false;
              }
          });
      });

  </script>
@endpush
