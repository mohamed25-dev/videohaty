@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="mx-auto col-9">
              <input type="hidden" value="{{$video->id}}" id="videoId">
                <div class="vid-container mx-auto">
                    @foreach ($video->convertedvideos as $convertedVideo)
                        <video id="videoplayer"  controls style="{{$video->Longitudinal == 0 ? 'width: 100%; height: 75%' : 'width: 800px; height: 510px' }}">
                            @if ($video->quality == 1080)
                                <source id="mp4-source" src="{{ Storage::url($convertedVideo->mp4_Format_1080) }}"
                                    type="video/mp4">
                            @elseif ($video->quality == 720)
                                <source id="mp4-source" src="{{ Storage::url($convertedVideo->mp4_Format_720) }}"
                                    type="video/mp4">
                            @elseif ($video->quality == 480)
                                <source id="mp4-source" src="{{ Storage::url($convertedVideo->mp4_Format_480) }}"
                                    type="video/mp4">
                            @elseif ($video->quality == 360)
                                <source id="mp4-source" src="{{ Storage::url($convertedVideo->mp4_Format_360) }}"
                                    type="video/mp4">
                            @else
                                <source id="mp4-source" src="{{ Storage::url($convertedVideo->mp4_Format_240) }}"
                                    type="video/mp4">
                            @endif
                        </video>
                    @endforeach
                </div>
                <select name="qualityPick" id="qualityPick">
                  <option value="1080" {{$video->quality == 1080 ? 'selected': ''}} {{$video->quality < 1080 ? 'hidden' : ''}}>1080p</option>
                  <option value="720" {{$video->quality == 720 ? 'selected': ''}} {{$video->quality < 720 ? 'hidden' : ''}}>720p</option>
                  <option value="480" {{$video->quality == 480 ? 'selected': ''}} {{$video->quality < 480 ? 'hidden' : ''}}>480p</option>
                  <option value="360" {{$video->quality == 360 ? 'selected': ''}} {{$video->quality < 360 ? 'hidden' : ''}}>360p</option>
                  <option value="240" {{$video->quality == 240 ? 'selected': ''}}>240p</option>
                </select>

                <div class="title mt-3">
                  <h5>
                    {{$video->title}}
                  </h5>
                </div>

                <div class="interaction text-center mt-5">
                  <a href="{{ route('likes') }}" class="like ml-2">
                    @if ($userLike)
                      @if ($userLike->like == 1)
                        <i class="far fa-thumbs-up fa-2x liked"></i>
                      @else 
                        <i class="far fa-thumbs-up fa-2x"></i>
                      @endif
                    @else 
                      <i class="far fa-thumbs-up fa-2x"></i>
                    @endif
                    <span id="countLikes">{{$countLikes}}</span>
                  </a>
                    |
                  <a href="{{ route('likes') }}" class="like mr-2">
                    @if ($userLike)
                      @if ($userLike->like == 0)
                        <i class="fas fa-thumbs-down fa-2x liked"></i>
                      @else
                        <i class="fas fa-thumbs-down fa-2x"></i>
                      @endif
                    @else 
                      <i class="fas fa-thumbs-down fa-2x"></i>
                    @endif
                    <span id="countDislikes">{{$countDislikes}}</span>
                  </a>

                  @foreach ($video->views as $view)
                      <span class="float-right">عددالمشاهدات
                      <span class="views-number"> {{$view->views_number}} </span>
                      </span>
                  @endforeach
                </div>

                <div class="login-alert mt-3">

                </div>

                <div class="mt-4 px-2">
                  <div class="comments">
                    <div class="mb-3">
                        <span>التعليقات</span>
                    </div>
                    <div>
                        <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="إضافة تعليق عام"></textarea>
                        <button type="submit" class="btn btn-info mt-3 saveComment">تعليق</button>
                        
                        <div class="commentAlert mt-5">
                    
                        </div>

                        <div class="commentBody">
                            @foreach($comments as $comment)
                                <div class="card mt-5 mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2">
                                                <img src="{{$comment->user->profile_photo_url}}" width="150px" class="rounded-full"/>
                                            </div>
                                            <div class="col-10">
                                                @if (Auth::check())
                                                    @if ($comment->user_id == auth()->user()->id || auth()->user()->administration_level > 0)
                                                        @if (!auth()->user()->block)
                                                            <form method="POST" action="{{route('comments.destroy', $comment->id)}}" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف التعليق هذا؟')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="float-left"><i class="far fa-trash-alt text-danger fa-lg"></i></button>
                                                            </form>

                                                            <form method="GET" action="{{route('comments.edit', $comment->id)}}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="float-left"><i class="far fa-edit text-success fa-lg ml-3"></i></button>
                                                            </form>
                                                        @endif   
                                                    @endif
                                                @endif
                                                <p class="mt-3 mb-2"><strong>{{$comment->user->name}}</strong></p> 
                                                <i class="far fa-clock"></i> <span class="comment_date text-secondary">{{$comment->created_at->diffForHumans()}}</span>
                                                <p class="mt-3" >{{$comment->body}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
      document.getElementById('qualityPick').onchange = function () {changeQuality()};
      
      function changeQuality () {
        let video = document.getElementById('videoplayer');
        let currentTime = video.currentTime;
        let selected = document.getElementById('qualityPick').value;
        let source;

        if (selected == '1080') {
          source = document.getElementById('mp4-source').src = "{{Storage::url($convertedVideo->mp4_Format_1080)}}";
        } else if (selected == '720') {
          source = document.getElementById('mp4-source').src = "{{Storage::url($convertedVideo->mp4_Format_720)}}";
        } else if (selected == '480') {
          source = document.getElementById('mp4-source').src = "{{Storage::url($convertedVideo->mp4_Format_480)}}";
        } else if (selected == '360') {
          source = document.getElementById('mp4-source').src = "{{Storage::url($convertedVideo->mp4_Format_360)}}";
        } else {
          source = document.getElementById('mp4-source').src = "{{Storage::url($convertedVideo->mp4_Format_240)}}";
        }

        video.load();
        video.play();
        video.currentTime = currentTime;
      }
    </script>

    <script>
      $('.like').on('click', function (event) {
        event.preventDefault();

        let token = '{{ Session::token() }}';
        let urlLike = '{{ route("likes") }}';
        let videoId = $('#videoId').val();

        let isAuthorized = '{{ (Auth::user()) ? true : false }}';
        let blocked = "{{{ (Auth::user()) ? (Auth::user()->block) ? 1 : 0 : 2}}}";

        if (!isAuthorized) {
          let html = 
          '<div class="alert alert-danger">' +
            '<ul>' +
              '<li class="login-alert">' +
                "المستخدمين المسجلين فقط لديهم القدرة على التفاعل مع المحتوى" +
              '</li>' +
            '</ul>' +
          '</div>';

          $('.login-alert').html(html);

        } else if (blocked == '1') {
          let html = 
          '<div class="alert alert-danger">' +
            '<ul>' +
              '<li class="login-alert">' +
                "أنت ممنوع من التفاعل مع المحتوى, رجاء تواصل مع الإدارة لمعرفة السبب" +
              '</li>' +
            '</ul>' +
          '</div>';

          $('.login-alert').html(html);
        } else {
          let pressedButton = event.target.parentNode.previousElementSibling == null;
          $.ajax({
            method: 'POST',
            url: urlLike,
            data: {
              '_token': token,
              'isLike': pressedButton,
              'videoId': videoId,
            },
            success: function(data) {
              $(event.target).toggleClass('liked');

              if ($(event.target).hasClass('fa-thumbs-down')) {
                $('.fa-thumbs-up').removeClass('liked');
              } else {
                $('.fa-thumbs-down').removeClass('liked');
              }

              $('#countLikes').html(data.countLikes);
              $('#countDislikes').html(data.countDislikes);
            }
          });
        }
      });
    </script>

    <script>
      $('#videoplayer').on('ended', function(event) {
        event.preventDefault();
        console.log('videoended')
        let token = '{{ Session::token() }}';
        let url = '{{ route("incrementViews") }}';
        let videoId = $('#videoId').val();

          $.ajax({
            method: 'POST',
            url: url,
            data: {
              '_token': token,
              'videoId': videoId,
            },
            success: function(data) {

              $('.views-number').html(data.viewsNumber);
            }
          });
      });
    </script>

<script>
  $('.saveComment').on('click', function(event) {
      let token = '{{ Session::token() }}';
      let urlComment = '{{ route('comments.store') }}';
      let videoId = 0;
      let AuthUser = "{{{ (Auth::user()) ? 0 : 1 }}}";
      let blocked = "{{{ (Auth::user()) ? (Auth::user()->block) ? 1 : 0 : 2}}}";
      
      if (AuthUser == '1') {
          event.preventDefault();
          let html='<div class="alert alert-danger">\
                  <ul>\
                      <li>يجب تسجيل الدخول لكي تستطيع التعليق على الفيديو</li>\
                  </ul>\
              </div>';
          $(".commentAlert ").html(html);
      }
      else if (blocked == '1') {
          var html='<div class="alert alert-danger">\
                      <ul>\
                          <li class="commentAlert">أنت ممنوع من التعليق</li>\
                      </ul>\
                  </div>';
          $(".commentAlert ").html(html);
         
      }
      else if ($('#comment').val().length == 0) {
          var html='<div class="alert alert-danger">\
                  <ul>\
                      <li>الرجاء كتابة تعليق</li>\
                  </ul>\
              </div>';
          $(".commentAlert ").html(html);  
      }
      else {
          $(".commentAlert ").html('');
          event.preventDefault();
          videoId = $("#videoId").val();
          comment = $("#comment").val();
          $.ajax({
              method: 'POST',
              url: urlComment,
              data: {
                  comment: comment, 
                  videoId: videoId, 
                  _token: token
              },
              success : function(data) { 
                  $("#comment").val('');
                  destroyUrl = "{{route('comments.destroy', 'des_id')}}";
                  destroy = destroyUrl.replace('des_id', data.commentId);
                  editUrl = "{{route('comments.edit', 'id')}}";
                  url = editUrl.replace('id', data.commentId);
                  var html='  <div class="card mt-5 mb-3">\
                                  <div class="card-body">\
                                      <div class="row">\
                                          <div class="col-2">\
                                              <img src="'+data.userImage+'" width="150px" class="rounded-full"/>\
                                          </div>\
                                          <div class="col-10">\
                                              <form method="GET" action="'+destroy+'">\
                                                  @csrf\
                                                  @method('DELETE')\
                                                  <button type="submit" class="float-left"><i class="far fa-trash-alt text-danger fa-lg"></i></button>\
                                              </form>\
                                              <form method="GET" action="'+url+'">\
                                                  @csrf\
                                                  @method('PATCH')\
                                                  <button type="submit" class="float-left"><i class="far fa-edit text-success fa-lg ml-3"></i></button>\
                                              </form>\
                                              <p class="mt-3 mb-2"><strong>'+data.userName+'</strong></p>\
                                              <i class="far fa-clock"></i> <span class="comment_date text-secondary">'+data.commentDate+'</span>\
                                              <p class="mt-3" >'+comment+'</p>\
                                          </div>\
                                      </div>\
                                  </div>\
                              </div>';
                  $(".commentBody").prepend(html);
                  
                    
              }
          })  
      }      
  });
</script>
@endsection