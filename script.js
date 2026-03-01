// script.js

// ページ読み込み時に保存された設定を適用
function applySavedFontSize() {
    const savedFontSize = localStorage.getItem('fontSize');
    if (savedFontSize) {
        document.body.classList.remove('font-small', 'font-large'); // 一旦リセット
        document.body.classList.add(savedFontSize);
    }
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', applySavedFontSize);

// 文字サイズを変更する関数
function changeFontSize(size) {
    // 古いクラスを削除
    document.body.classList.remove('font-small', 'font-large');
    
    // 新しいクラスを追加
    if (size !== 'font-medium') {
        document.body.classList.add(size);
    }
    
    // 設定を保存
    localStorage.setItem('fontSize', size);
}

// ★追加：ページが表示されたとき（戻るボタンで戻った時など）にも適用★
window.addEventListener('pageshow', applySavedFontSize);