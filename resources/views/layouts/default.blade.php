<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Weibo App')- Laravel 新手入门教程</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    @include('layouts._header')
    <div class="container">
        <div class="offset-md-1 col-md-10">
            @include('shared._messages')
            @yield('content')
            @include('layouts._footer')
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/captcha.js') }}"></script>
    <script !src="">
        function getCaptcha() {
            // alert('已经获取到验证码');
            $.ajax({
                type: 'get',
                url: "{{ route('getImg') }}",
                dataType: 'json',
                success: function (result) {
                    var img = $(`<img src="" class="img-rounded captcha" alt="Responsive image" onclick="getCaptcha()" >
                `);
                    img.attr("src", result.img); //设置验证码图片
                    $('#captcha-fa').append(img);
                    // 找到最后一个删除前面的
                    $('#captcha-fa>img').last().prevAll('img').remove();

                }
            });
        }
        // DOM就绪时执行
        $('.captcha').ready(function () {
            getCaptcha();
        })

    </script>
</body>
</html>
