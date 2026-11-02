CSSにはるだけ。

2. ロゴのフェードイン

JSはいりません。CSSだけで動きます。

仕組み

@keyframes で「最初は透明、最後は不透明」という動きの設計図を作り、animation で「その設計図を実行して」とお願いします。ページを開いた時点で、自動で動きます。

導入のしかた

ステップ1：HTMLを入れる

html

<div class="fv">
  <img class="fv__logo" src="img/logo.png" alt="ロゴ" />
</div>

ステップ2：CSSを入れる

css
.fv {
position: relative;
height: 100vh;
}

.fv\_\_logo {
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);

/_ 動き出す前は、透明にしておく _/
opacity: 0;
/_ 名前 / かかる時間 / 動き方 / 待ち時間 / 終わったらそのまま _/
animation: fadeIn 1.5s ease 0.5s forwards;
}

@keyframes fadeIn {
from { opacity: 0; }
to { opacity: 1; }
}
変えられるところ
変えたいこと 触る場所
ゆっくり／速く 1.5s の数字
出るまでの待ち時間 0.5s の数字
動き方 ease（linear、ease-in-out など）
動かないときのチェック
一瞬見えてから消える → opacity: 0 を書き忘れていないか
出たあと消える → forwards を書き忘れていないか
transition との違い
きっかけ 例
transition クラスが付いたなど、何かが起きたとき ハンバーガー
animation ページを開いたときに、自動で ロゴのフェードイン
