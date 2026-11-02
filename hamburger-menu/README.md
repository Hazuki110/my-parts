HTMLを貼り付けいじってnavに class="global-nav" を貼り付け、CSSとjsを貼るだけ。

<script src="js/main.js"></script> を</body>の前に貼り付けるの忘れずに。


1. ハンバーガーメニュー
   仕組み
   ボタンを押す
   JSが、ボタンとメニューに is-open を付ける（もう一度押すと外す）
   CSSが、is-open のときの見た目（×印、全画面表示）を決める
   transition のおかげで、ふわっと動く

メニューは最初から画面いっぱいに存在していて、opacity: 0（透明）と visibility: hidden（触れない）で隠しているだけです。display: none だと、ふわっと出る動きができません。

導入のしかた

ステップ1：HTMLを入れる

<header> の中に、ボタンとメニューを置きます。

html

<header>
  <button
    class="hamburger"
    type="button"
    aria-label="メニューを開く"
    aria-expanded="false"
    aria-controls="global-nav"
  >
    <span></span>
    <span></span>
    <span></span>
  </button>

  <nav class="nav" id="global-nav">
    <ul class="nav__list">
      <li><a href="#menu">メニュー1</a></li>
      <li><a href="#news">メニュー2</a></li>
      <li><a href="#info">メニュー3</a></li>
    </ul>
  </nav>
</header>

ステップ2：CSSを入れる

css
header {
position: relative;
}

/_ ハンバーガーボタン _/
.hamburger {
position: absolute;
top: 0;
right: 0;
z-index: 100;
width: 80px;
height: 80px;
cursor: pointer;
}

.hamburger span {
position: absolute;
left: 24px;
width: 32px;
height: 1px;
background-color: #2c2c2c;
transition: top 0.3s, transform 0.3s, opacity 0.3s, background-color 0.3s;
}
.hamburger span:nth-child(1) { top: 30px; }
.hamburger span:nth-child(2) { top: 40px; }
.hamburger span:nth-child(3) { top: 50px; }

/_ 開いたとき：×印にする _/
.hamburger.is-open span { background-color: #fff; }
.hamburger.is-open span:nth-child(1) { top: 40px; transform: rotate(45deg); }
.hamburger.is-open span:nth-child(2) { opacity: 0; }
.hamburger.is-open span:nth-child(3) { top: 40px; transform: rotate(-45deg); }

/_ 全画面メニュー（最初は透明で、触れない） _/
.nav {
position: fixed;
inset: 0;
z-index: 50;
background-color: #2c2c2c;
opacity: 0;
visibility: hidden;
transition: opacity 0.4s, visibility 0.4s;
}
.nav.is-open {
opacity: 1;
visibility: visible;
}

.nav**list {
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
gap: 56px;
height: 100%;
}
.nav**list a {
color: #fff;
font-size: 20px;
letter-spacing: 0.15em;
}

/_ 開いている間は、背面をスクロールさせない _/
.is-lock { overflow: hidden; }

ステップ3：JSを入れる

js/main.js を作って、貼ります。</body> の直前で読み込みます。

html

<script src="js/main.js"></script>

js
const hamburger = document.querySelector(".hamburger");
const nav = document.querySelector(".nav");
const navLinks = nav.querySelectorAll("a");

function setMenu(isOpen) {
hamburger.classList.toggle("is-open", isOpen);
nav.classList.toggle("is-open", isOpen);
document.body.classList.toggle("is-lock", isOpen);
hamburger.setAttribute("aria-expanded", String(isOpen));
hamburger.setAttribute("aria-label", isOpen ? "メニューを閉じる" : "メニューを開く");
}

// ボタンで開閉
hamburger.addEventListener("click", () => {
setMenu(!hamburger.classList.contains("is-open"));
});

// メニューのリンクを押したら閉じる
navLinks.forEach((link) => {
link.addEventListener("click", () => setMenu(false));
});
PC幅で横並びのナビにしたいとき

768px以上では、ボタンを消して、メニューを普通の横並びに戻します。

css
@media screen and (min-width: 768px) {
.hamburger { display: none; }

.nav {
position: static;
background: none;
opacity: 1;
visibility: visible;
}

.nav\_\_list {
flex-direction: row;
gap: 0;
}

.nav\_\_list a {
color: #2c2c2c;
font-size: 16px;
padding: 0 30px;
}
}

さらに、PC幅に広げたときにメニューが開きっぱなしにならないよう、JSに次を足します。

js
window.addEventListener("resize", () => {
if (window.innerWidth >= 768) setMenu(false);
});
変えられるところ
変えたいこと 触る場所
線の太さ .hamburger span の height
線の間隔 .hamburger span:nth-child(◯) の top
開くスピード .nav の transition の秒数
メニューの背景色 .nav の background-color
文字の大きさ .nav\_\_list a の font-size
動かないときのチェック
押しても何も起きない → JSのクラス名（.hamburger、.nav）がHTMLと同じか
メニューが最初から見えている → .nav に opacity: 0 と visibility: hidden があるか
開いても×にならない → .hamburger.is-open span のCSSがあるか
Consoleに赤いエラー → main.js のパスと、大文字小文字を確認
