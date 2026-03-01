// script.js

// ページ読み込み時に保存された設定を適用
document.addEventListener('DOMContentLoaded', () => {
    const savedFontSize = localStorage.getItem('fontSize');
    if (savedFontSize) {
        document.body.classList.add(savedFontSize);
    }
});

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