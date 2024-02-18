import $ from "cash-dom"

$(function () {
    const wrapper = $('#toc')
    const toc = $('.table-of-contents')

    toc.remove()
    wrapper.append(toc)
})
