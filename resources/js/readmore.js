import $ from 'jquery';

// function convertMarkdownHashesToHeadings() {
//     const $content = $('.content-body');
//     if (!$content.length) return;

//     let html = $content.html();    

//     if (/\<h[1-6]/i.test(html)) return;
//     html = html.replace(/^(#{1,6})\s*(.+)$/gm, function (_, hashes, text) {
//         const level = Math.min(hashes.length, 6);        
//         return '<h' + level + '>' + text.trim() + '</h' + level + '>';
//     });
//     $content.html(html);
// }

function addHeadingIdsFromTOC() {

    const $content = $('.content-body');
    const $headings = $content.find('h2, h3, h4');

    const tocData = window.tocData || [];

    if (tocData.length === 0) {
        $headings.each(function (index) {
            if (!this.id) {
                const id = 'section-' + (index + 1) + '-' + $(this).text().toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-');
                this.id = id;
            }
        });
        return;
    }

    $headings.each(function (index) {
        const $heading = $(this);
        const headingText = $heading.text().trim();

        const tocItem = tocData.find(item =>
            item.title === headingText ||
            headingText.includes(item.title) ||
            item.title.includes(headingText)
        );

        if (tocItem) {
            this.id = tocItem.slug || ('section-' + tocItem.id);
        } else if (!this.id) {
            this.id = 'section-' + (index + 1) + '-' + headingText.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-');
        }
    });
}

function initTOCHighlight() {
    const $tocLinks = $('#tocList a.anchor-link');
    if (!$tocLinks.length) return;

    $(window).off('.toc');
    $tocLinks.off('.toc');

    const sections = [];
    let activeId = null;
    let isScrolling = false;
    let rafLock = false;
    const OFFSET = 100;
    const ACTIVE_OFFSET = 150;

    function calculateSections() {
        sections.length = 0;

        $tocLinks.each(function () {
            const id = this.getAttribute('href');
            if (!id || id[0] !== '#') return;

            const target = document.getElementById(id.slice(1));
            if (!target) return;

            sections.push({
                id,
                el: target,
                top: target.getBoundingClientRect().top + window.pageYOffset
            });
        });
    }

    calculateSections();

    /* ---------------- CLICK HANDLER ---------------- */
    $tocLinks.on('click.toc', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        if (isScrolling) return;

        const id = this.getAttribute('href');
        const section = sections.find(s => s.id === id);
        if (!section) return;

        isScrolling = true;
        window.scrollTo({
            top: section.top - OFFSET,
            behavior: 'smooth'
        });

        setActive(id);

        setTimeout(() => {
            isScrolling = false;
        }, 600);
    });

    $(window).on('scroll.toc', function () {
        if (isScrolling || rafLock) return;

        rafLock = true;

        requestAnimationFrame(() => {
            const scrollPos = window.pageYOffset + ACTIVE_OFFSET;
            let currentId = null;

            for (let i = 0; i < sections.length; i++) {
                if (scrollPos >= sections[i].top) {
                    currentId = sections[i].id;
                }
            }

            if (currentId && currentId !== activeId) {
                // setActive(currentId);
            }

            rafLock = false;
        });
    });

    $(window).on('resize.toc load.toc', calculateSections);

    function setActive(id) {
        console.log('Setting active TOC item:', id);
        activeId = id;

        $tocLinks.removeClass(
            'active bg-unik-primary/10 border-unik-primary'
        );

        $tocLinks
            .filter(`[href="${id}"]`)
            .addClass('active bg-unik-primary/10 border-unik-primary');
    }
}

function enhanceContent() {
    const $content = $('.content-body');
    if (window.tocData && window.tocData.length > 0) {
        $content.find('h2, h3, h4').each(function () {
            if (this.id) {
                const $heading = $(this);
                $heading.prepend(`
                    <a href="#${this.id}" class="anchor-link no-underline text-unik-primary/30 hover:text-unik-primary mr-2">
                        <i class="fas fa-link text-sm"></i>
                    </a>
                `);
            }
        });
    }

    // Style paragraphs after headings
    $content.find('h2 + p, h3 + p, h4 + p').addClass('first-line:font-large first-line:text-[#0013a0] first-line:font-semibold');

    // Style lists
    $content.find('ul').addClass('space-y-2 pl-6');
    $content.find('ul li').addClass('relative pl-2');
    // $content.find('ul li:before').remove();
    // $content.find('ul li').each(function () {
    //     $(this).prepend('<span class="absolute -left-4 text-unik-primary">•</span>');
    // });

    $content.find('ol').addClass('space-y-2 pl-8 list-decimal');
    $content.find('ol li').addClass('pl-2');

    // Style blockquotes
    $content.find('blockquote').addClass('border-l-4 border-unik-primary bg-unik-light/30 p-6 my-8 italic rounded-r-lg');

    // Style code blocks
    $content.find('pre').addClass('bg-unik-dark text-unik-light p-4 rounded-lg overflow-x-auto my-6');
    $content.find('code').not('pre code').addClass('bg-unik-dark/10 text-unik-dark px-2 py-1 rounded text-sm');

    // Style tables
    $content.find('table').addClass('w-full border-collapse my-8 rounded-lg overflow-hidden shadow-sm');
    $content.find('table th').addClass('bg-unik-primary text-white p-4 font-semibold text-left');
    $content.find('table td').addClass('p-4 border border-unik-border');
    $content.find('table tr:nth-child(even)').addClass('bg-unik-light/20');

    // Style images
    $content.find('img').addClass('rounded-lg shadow-md my-8 max-w-full h-auto mx-auto');

    // Style links
    $content.find('a').addClass('text-unik-primary underline underline-offset-2 hover:text-unik-secondary transition-colors');

    // Add line spacing to paragraphs
    $content.find('p').addClass('mb-6 leading-relaxed');
}

$(document).ready(function () {

    // convertMarkdownHashesToHeadings();
    addHeadingIdsFromTOC();
    enhanceContent();

    $('#tocList').before(`
        <button id="tocToggle" class="lg:hidden w-full mb-4 py-2 px-4 bg-unik-primary text-white rounded-lg flex items-center justify-center gap-2">
            <i class="fas fa-bars"></i> Show/Hide Table of Contents
        </button>
    `);

    $('#tocToggle').on('click', function () {
        $('#tocList').slideToggle();
        $(this).find('i').toggleClass('fa-bars fa-times');
    });

    if ($('#tocList a').length > 0) {
        initTOCHighlight();
    }

});