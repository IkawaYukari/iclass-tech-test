function popup(e){
    let pos = e.currentTarget.firstElementChild;
    pos.style.left = e.pageX + 'px';
    pos.style.top = e.pageY + 'px';
}