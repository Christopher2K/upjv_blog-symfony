var HIDDEN_CLASS = 'hidden';

/**
 * Return the comment panel correponding to the comment ID targeted
 * @param id - Comment ID
 * @returns {*|jQuery|HTMLElement}
 */
var _getCommentPanelByCommentId = function (id) {
    return $('.comment-show[data-comment_id="'+ id +'"]');
};

/**
 * Return the modify comment form
 * @param id
 */
var _getCommentFormByCommentId = function (id) {
    return $('.comment-form[data-comment_id="' + id + '"]');
};

/**
 * Hide a displayed element
 * @param element {*|jQuery|HTMLElement}
 * @returns {*|jQuery|HTMLElement}
 * @private
 */
var _hideElement = function (element) {
    element.addClass(HIDDEN_CLASS);
    return element;
};

/**
 * Show an hidden element
 * @param element {*|jQuery|HTMLElement}
 * @returns {*|jQuery|HTMLElement}
 * @private
 */
var _showElement = function (element) {
    element.removeClass(HIDDEN_CLASS);
    return element;
};

$(function () {
    $('.options-modify').click(function (e) {
        var commentId = $(e.target).attr('data-comment_id');
        _hideElement(_getCommentPanelByCommentId(commentId));
        _showElement(_getCommentFormByCommentId(commentId));
    });
});