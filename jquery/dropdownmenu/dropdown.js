$(function(){
    $('#menu li').hover(function(){
        $("ul:not(:animated)", this).slideDown("fast");
    }, function(){
        $("ul.child",this).slideUp();
    });
});



$(function() {
  /**
   * クリックによる開閉
   */
  // 基本的にページ内のどこをクリックしても全てのサブメニューを閉じるようにしておく。
  $(document).click(function() {
    $('#menu-click > li > ul').slideUp();
  });

  // クリックによる開閉の場合、親メニューの<a>要素の機能は必要ないので無効にする。
  $('#menu-click > li > a').click(function(ev) {
    ev.preventDefault();
  });

  $('#menu-click > li').click(function(ev) {
    var sub = $(this).children('ul');
    if ($(sub).is(':hidden')) {
      // 今回はこれからサブメニューを開きたい項目をクリックしているので、
      // 上記の全てのサブメニューを閉じるイベントを発火させてはならない。
      // よって、イベントのバブリングを中止する。
      ev.stopPropagation();

      // 他に開いているサブメニューを閉じる。
      // 開いたままでもよければ、下の1行は必要ない。
      // $('#menu-click > li > ul:visible').slideUp();
      $('#menu-click > li > ul').slideUp();
    
      $(sub).slideDown();
    }
  });

  /**
   * マウスホバーによる開閉
   */
  $('#menu-mouseover > li').hover(
    function(){ $(this).children('ul').slideDown() },
    function(){ $(this).children('ul').slideUp() }
  );
});