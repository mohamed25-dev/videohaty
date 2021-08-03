@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="mx-auto col-10">
              <input type="hidden" value="{{$video->id}}" id="videoId">
                <div class="vid-container mx-auto">
                    @foreach ($video->convertedvideos as $convertedVideo)
                        <video id="videoplayer"  controls style="{{$video->Longitudinal == 0 ? 'width: 80%; height: 75%' : 'width: 800px; height: 510px' }}">
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
                  <a href="#" class="like ml-2">
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
                  <a href="#" class="like mr-2">
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
        if (!isAuthorized) {
          console.log('not authorized')
          let html = 
          '<div class="alert alert-danger">' +
            '<ul>' +
              '<li class="login-alert">' +
                "المستخدمين المسجلين فقط لديهم القدرة على التفاعل مع المحتوى" +
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
@endsection