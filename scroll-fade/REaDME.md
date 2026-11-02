動かしたい場所に class="js-fade" をタグにつけてCSSを貼るだけ、あとJSもはるだけ。

<script src="js/main.js"></script> を</body>の前に忘れずに。ハンバーガーと共通。一つでOK

3. スクロールでふわっと出る
仕組み
動かしたい要素に、目印として js-fade を付ける
JSの IntersectionObserver（見張り番）が、要素が画面に入ったのを見つける
見つけたら、is-show を付ける
CSSが、is-show のときの見た目（元の位置に戻って、濃くなる）を決める

ハンバーガーと同じ、JSがクラスを付ける → CSSが動かすの形です。

導入のしかた

ステップ1：HTMLに目印をつける

ふわっと出したい要素に、js-fade を足します。

html
<section class="section js-fade">...</section>

ステップ2：CSSを入れる

css
/* 最初は透明で、少し下にいる */
.js-fade {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s, transform 0.8s;
}

/* is-show が付いたら、元の位置に戻って見える */
.js-fade.is-show {
  opacity: 1;
  transform: translateY(0);
}

ステップ3：JSを入れる

js/main.js に貼ります（ハンバーガーと同じファイルで大丈夫です）。

js
const fadeTargets = document.querySelectorAll(".js-fade");

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-show");
        observer.unobserve(entry.target); // 1回出たら、見張りをやめる
      }
    });
  },
  { threshold: 0.15 } // 15%見えたら発動
);

fadeTargets.forEach((target) => observer.observe(target));
変えられるところ
変えたいこと	触る場所
動くスピード	CSSの 0.8s
下からの距離	CSSの translateY(30px)
出るタイミング	JSの threshold（0〜1。大きいほど遅く出る）
動かないときのチェック
ずっと透明で、出てこない → JSが読み込まれていない（パスと大文字小文字を確認）
縦に長い要素が出てこない → threshold を小さくする（0.1 くらい）
親と子の両方に js-fade を付けている → 二重にかかるので、どちらか片方にする