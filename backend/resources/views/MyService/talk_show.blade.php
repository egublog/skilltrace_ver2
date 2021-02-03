@extends('layouts.default')

@section('css', '/css/MyService/talk_show.css')

@section('title', 'トークの中身')

@section('content')

<!-- トーク -->

<!-- 飛ばすリンク先 -->
<!-- friend -->
<!-- profile -->

<section class="talk container">

  <div class="row">
    <div class="friends col-md-4 hidden-sp">
  
      <!-- 友達一覧 -->
      <ul class="friends-list">
  
        @forelse($following_accounts as $following_account)
  
        <li class="friends-item">
          <form name="friend" action="{{ action('TalkController@talk_show') }}" method="GET">
            @csrf
  
            @if(count($following_accounts) == 1)
            <a href="javascript: friend.submit()">
              @endif
  
              @if(count($following_accounts) >= 2)
              <a href="javascript: friend[{{ $loop->iteration - 1 }}].submit()">
                @endif
  
  
  
  
                <div class="friend-img">
                  @if($following_account->user_following->img == null)
                  <img src="/storage/no_img.png" alt="各々のトプ画">
                  @else
                  <img src="/storage/profile_images/{{ $following_account->user_following->img }}" alt="自分のトプ画">
                  @endif
                </div><!-- /.friends-img -->
  
                <div class="friends-body">
                  <!-- 名前や年齢などの説明 -->
                  <div class="friends-top">
                    <p class="friends-top-name">{{{ $following_account->user_following->name }}}</p>
                    <p class="friends-top-age">年齢：{{{ $following_account->user_following->age }}}</p>
                  </div><!-- /.friends-top -->
  
                  <div class="friends-bottom">
                    <p class="friends-bottom-area">住所：{{{ $following_account->user_following->area->area }}}</p>
                    <p class="friends-bottom-history">エンジニア歴：{{{ $following_account->user_following->history->history }}}</p>
                    <p class="friends-bottom-favorite">得意言語：{{{ $following_account->user_following->language->name }}}</p>
                  </div><!-- /.friends-bottom -->
  
                </div><!-- /.friends-body -->
  
  
              </a>
  
              <input type="hidden" name="id" value="{{ optional($following_account->user_following)->id }}">
  
          </form>
  
        </li><!-- /.friends-item -->
  
        @empty
  
        <p>見つかりませんでした</p>
  
        @endforelse
  
      </ul><!-- /.friends-list -->
    </div><!-- /.friends -->
  
  
    <!-- 右側 -->
  
    <div class="talk-friend col-md-8">
  
      <div class="talk-friend-top">
  
        <div class="talk-friend-top-ttl">
          <a class="back" href="{{ action('TalkController@talk') }}"><span>&lt;</span></a>
  
          <p>{{ $theFriendAccount->name }}とのトーク</p>
  
        </div><!-- /.talk-friend-top-ttl -->
  
      </div><!-- /.talk-friend-top -->
  
      <div class="talk-friend-middle">
        <!-- もしも自分の発言だったら -->
        @if(isset($talkDates))
        @foreach($talkDates as $talkDate)
        @if($talkDate->user_id == $myId)
        <div class="talk-own">
          <div class="talk-own-head">
  
            <!-- もしもyetがtrueだったら -->
            <!-- もし、相手がTalkController@talk_showを実行したら -->
  
            @if($talkDate->yet)
            <p class="talk-own-head-yet">既読</p>
            @endif
  
            <p class="talk-own-head-time">{{ $talkDate->created_at->format('H:i') }}</p>
  
          </div>
  
          <div class="talk-own-content">
            <p class="talk-own-content-txt">{{ $talkDate->talk_body }}</p>
          </div>
  
        </div>
  
        <!-- もしも相手の発言だったら -->
        @else
        <div class="talk-opponent">
  
          <div class="talk-opponent-img">
            @if($theFriendAccount->img == null)
            <img src="/storage/no_img.png" alt="各々のトプ画">
            @else
            <img src="/storage/profile_images/{{ $theFriendAccount->img }}" alt="自分のトプ画">
            @endif
          </div>
  
          <div class="talk-opponent-body">
            <p class="talk-opponent-body-txt">{{ $talkDate->talk_body }}</p>
          </div>
  
          <div class="talk-opponent-footer">
            <p class="talk-opponent-footer-time">{{ $talkDate->created_at->format('H:i') }}</p>
          </div>
  
        </div>
        @endif
        @endforeach
        @endif
  
      </div><!-- /.talk-friend-middle -->
  
      <div class="talk-friend-bottom">
        <form action="{{ action('TalkController@talk_content') }}" method="post">
          @csrf
          <div class="talk-send">
  
            <input type="hidden" name="id" value="{{ $theFriendAccount->id }}">
  
            <textarea name="message" id="message" resize="vertical" placeholder="メッセージを入力"></textarea>
  
            <div class="talk-send-button">
              <input class="" type="submit" value="送信">
            </div>
  
          </div>
        </form>
      </div><!-- /.talk-friend-bottom -->
  
    </div><!-- /.talk-friend -->
  </div><!-- /.row -->
</section><!-- /.talk -->


@endsection