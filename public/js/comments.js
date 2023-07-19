const showCommentSectionButton = document.getElementById('show-comment-section');
const commentSection = document.getElementById('comment-section');
showCommentSectionButton.addEventListener('click', () => {
    commentSection.style.display = commentSection.style.display === 'none' ? 'block' : 'none';
});
