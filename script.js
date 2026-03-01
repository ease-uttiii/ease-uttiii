// script.js

// ページ読み込み時に保存された設定を自動で適用する処理
document.addEventListener('DOMContentLoaded', () => {
    applySavedFontSize();
});

// 文字サイズを実際にCSSクラスとして適用する関数
function applySavedFontSize() {
    const savedFontSize = localStorage.getItem('fontSize');
    // 古いクラスを一旦削除（font-small, font-large）
    document.body.classList.remove('font-small', 'font-large');
    
    // 保存されたサイズがあれば追加
    if (savedFontSize) {
        document.body.classList.add(savedFontSize);
    }
}

// 文字サイズを変更するボタンを押した時に実行する関数
function changeFontSize(size) {
    // 選択されたサイズを保存
    localStorage.setItem('fontSize', size);
    // 適用する
    applySavedFontSize();
}