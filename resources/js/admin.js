import $ from 'jquery';
window.$ = window.jQuery = $;

async function ensureSelect2Loaded() {
    if (typeof $.fn.select2 === 'function') {
        return true;
    }

    try {
        const module = await import('select2');

        if (typeof module.default === 'function') {
            module.default(window, $);
        }

        if (typeof $.fn.select2 === 'function') {
            return true;
        }
    } catch (error) {
        console.error('Failed to load Select2 module:', error);
    }

    return typeof $.fn.select2 === 'function';
}

function showAlert(type, message) {
    $('.alert-dismissible').remove();
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    var alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

    $('body').append(alertHtml);
    setTimeout(function () {
        $('.alert-dismissible').hide();
    }, 4000);
}

function removeImage(button, imagePath) {
    if (confirm('Are you sure you want to remove this image?')) {
        const imageItem = button.closest('.image-item');
        imageItem.style.transform = 'scale(0.8)';
        imageItem.style.opacity = '0';

        setTimeout(() => {
            imageItem.remove();
            const removedImagesInput = document.getElementById('removedImages');
            const removedImages = removedImagesInput.value ? removedImagesInput.value.split(',') : [];
            removedImages.push(imagePath);
            removedImagesInput.value = removedImages.join(',');
        }, 300);
    }
}

function removeNewImage(button) {
    const imageItem = button.closest('.image-item');
    imageItem.style.transform = 'scale(0.8)';
    imageItem.style.opacity = '0';

    setTimeout(() => {
        imageItem.remove();

        // Check if any new images left
        const newImagesContainer = document.getElementById('newImagesContainer');
        if (newImagesContainer.children.length === 0) {
            document.getElementById('newImagesPreview').style.display = 'none';
            document.getElementById('upload').value = '';
        }
    }, 300);
}

function updateStats(stats) {
    if (stats.total_posts) {
        $('.stats-card:first-child .stats-number').text(stats.total_posts);
    }
    if (stats.drafts) {
        $('.stats-card:nth-child(2) .stats-number').text(stats.drafts);
    }
}

function updateCounters() {
    var titleCount = $('#postTitle').val().length;
    $('#titleCount').text(titleCount);

    var slugCount = $('#postSlug').val().length;
    $('#slugCount').text(slugCount);

    var descCount = $('#description').val().length;
    $('#descCount').text(descCount);

    var metaTitleCount = $('input[name="meta_title"]').val().length;
    $('#metaTitleCount').text(metaTitleCount);

    var metaDescCount = $('textarea[name="meta_description"]').val().length;
    $('#metaDescCount').text(metaDescCount);
}

async function initializeSelect2() {
    const loaded = await ensureSelect2Loaded();
    if (!loaded) {
        console.warn('Select2 is not available on $.fn. Skipping Select2 initialization.');
        return;
    }

    function initSelect2Element($element, options) {
        if (!$element.length) {
            return;
        }

        if ($element.hasClass('select2-hidden-accessible')) {
            $element.select2('destroy');
        }

        var dropdownParent = $element.closest('.form-container');
        if (!dropdownParent.length) {
            dropdownParent = $('body');
        }

        $element.select2($.extend({
            width: '100%',
            dropdownParent: dropdownParent
        }, options));
    }

    if ($('#tagsSelect').length) {
        console.log('Initializing tags Select2...');

        initSelect2Element($('#tagsSelect'), {
            placeholder: "Select or add tags",
            allowClear: true,
            tags: true,
            tokenSeparators: [',', ' ']
        });
    }

    if ($('#categorySelect').length) {
        console.log('Initializing category Select2...');

        initSelect2Element($('#categorySelect'), {
            placeholder: "Select a category",
            allowClear: true
        });
    }
}

function extractHeadings(content) {
    if (!content || !content.trim()) {
        return [];
    }

    var normalizedContent = content.replace(/\r\n/g, '\n').replace(/\r/g, '\n');
    var candidates = [];

    function lineFromIndex(text, index) {
        return text.slice(0, index).split('\n').length - 1;
    }

    var htmlRegex = /<h([2-4])[^>]*>([\s\S]*?)<\/h\1>/gi;
    var htmlMatch;
    while ((htmlMatch = htmlRegex.exec(normalizedContent)) !== null) {
        var htmlTitle = $('<div>').html(htmlMatch[2]).text().replace(/\s+/g, ' ').trim();
        if (!htmlTitle) {
            continue;
        }

        candidates.push({
            index: htmlMatch.index,
            title: htmlTitle,
            level: parseInt(htmlMatch[1], 10),
            line: lineFromIndex(normalizedContent, htmlMatch.index)
        });
    }

    var markdownRegex = /^(#{2,4})\s*(.+?)\s*$/gm;
    var markdownMatch;
    while ((markdownMatch = markdownRegex.exec(normalizedContent)) !== null) {
        var markdownTitle = markdownMatch[2].replace(/\s+/g, ' ').trim();
        if (!markdownTitle || markdownTitle.startsWith('#')) {
            continue;
        }

        candidates.push({
            index: markdownMatch.index,
            title: markdownTitle,
            level: markdownMatch[1].length,
            line: lineFromIndex(normalizedContent, markdownMatch.index)
        });
    }

    candidates.sort(function (a, b) {
        return a.index - b.index;
    });

    var headings = [];
    var seen = {};

    $.each(candidates, function (_, item) {
        var key = item.level + '::' + item.title.toLowerCase();
        if (seen[key]) {
            return;
        }

        seen[key] = true;
        headings.push({
            id: 'section-' + (headings.length + 1),
            title: item.title,
            level: item.level,
            line: item.line
        });
    });

    return headings;
}

function createSlug(text) {
    return text.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
}

function calculateReadingTime(content) {
    var words = content.trim().split(/\s+/).length;
    var readingTime = Math.max(1, Math.ceil(words / 200));
    return { words: words, readingTime: readingTime };
}

function generateTOC() {
    var content = $('#description').val();

    if (!content.trim()) {
        alert('Please enter some content first!');
        return;
    }

    var headings = extractHeadings(content);

    if (headings.length === 0) {
        alert('No headings found! Use markdown headings (##, ###, ####) or HTML headings (<h2>, <h3>, <h4>).');
        return;
    }

    var tocData = $.map(headings, function (heading, index) {
        return {
            id: heading.id,
            title: heading.title,
            level: heading.level,
            slug: createSlug(heading.title),
            order: index + 1
        };
    });

    $('#tableOfContents').val(JSON.stringify(tocData));

    var stats = calculateReadingTime(content);
    $('#wordCount').val(stats.words);
    $('#readingTime').val(stats.readingTime);

    // Update preview
    updateTOCPreview(tocData, stats.words, stats.readingTime);

    // Show preview and stats
    $('#tocPreviewCard').show();
    $('#tocStats').show();
}

function updateTOCPreview(tocData, words, readingTime) {
    var $tocPreviewList = $('#tocPreviewList');
    $tocPreviewList.empty();

    $.each(tocData, function (index, item) {
        var padding = (item.level - 2) * 20;
        var $li = $('<li>').addClass('mb-2').css('padding-left', padding + 'px');

        $li.html(`
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2" style="min-width: 24px;">${index + 1}</span>
                    <span class="small">${item.title}</span>
                </div>
            `);

        $tocPreviewList.append($li);
    });

    // Update stats
    $('#tocSectionCount').text(tocData.length + ' sections');
    $('#statSections').text(tocData.length);
    $('#statWords').text(words.toLocaleString());
    $('#statReadTime').text(readingTime + ' min');
}

function resetTOC() {
    $('#tableOfContents').val('');
    $('#wordCount').val('');
    $('#readingTime').val('');
    $('#tocPreviewCard').hide();
    $('#tocStats').hide();
    $('#tocPreviewList').empty();
}

function loadExistingTOC() {
    var existingTOC = $('#tableOfContents').val();
    var content = $('#description').val();

    if (existingTOC) {
        try {
            var tocData = JSON.parse(existingTOC);
            if (tocData.length > 0) {
                var stats = calculateReadingTime(content);
                $('#wordCount').val(stats.words);
                $('#readingTime').val(stats.readingTime);

                updateTOCPreview(tocData, stats.words, stats.readingTime);
                $('#tocPreviewCard').show();
                $('#tocStats').show();
            }
        } catch (e) {
            console.log('Error parsing existing TOC:', e);
        }
    }
}

function suggestMissingHeadings() {
    var content = $('#description').val();
    var existingTOC = $('#tableOfContents').val();
    var existingHeadings = [];

    if (existingTOC) {
        try {
            var tocData = JSON.parse(existingTOC);
            existingHeadings = tocData.map(function (item) {
                return item.title.toLowerCase();
            });
        } catch (e) {
        }
    }

    var lines = content.split('\n');
    var potentialHeadings = [];

    $.each(lines, function (index, line) {
        line = $.trim(line);
        if (line && line.length > 10 && line.length < 200) {
            if (!line.endsWith('.') && !line.endsWith('!') && !line.endsWith('?')) {
                var words = line.split(' ');
                if (words.length >= 2 && words.length <= 10) {
                    var firstWord = words[0];
                    if (firstWord === firstWord.toUpperCase() ||
                        firstWord.charAt(0) === firstWord.charAt(0).toUpperCase()) {

                        var lineLower = line.toLowerCase();
                        if ($.inArray(lineLower, existingHeadings) === -1) {
                            potentialHeadings.push(line);
                        }
                    }
                }
            }
        }
    });

    if (potentialHeadings.length > 0) {
        if (confirm('Found ' + potentialHeadings.length + ' potential headings not in TOC. Add them as H2?\n\n' +
            potentialHeadings.slice(0, 5).join('\n') +
            (potentialHeadings.length > 5 ? '\n...and ' + (potentialHeadings.length - 5) + ' more' : ''))) {

            var formattedContent = content;
            $.each(potentialHeadings, function (index, heading) {
                var regex = new RegExp('^' + heading.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '$', 'm');
                formattedContent = formattedContent.replace(regex, '## ' + heading);
            });

            $('#description').val(formattedContent);
            generateTOC();
        }
    }
}

function checkForMissingTOC() {
    var content = $('#description').val();
    var existingTOC = $('#tableOfContents').val();

    if (content && !existingTOC) {
        var headings = extractHeadings(content);
        if (headings.length > 0) {
            if (confirm('Found ' + headings.length + ' headings in content but no TOC. Generate TOC now?')) {
                generateTOC();
            }
        }
    }
}

$(document).ready(async function () {
    await initializeSelect2();
    loadExistingTOC();
    checkForMissingTOC();
    console.log("Admin JS is ready!");
    // setTimeout(() => {
    //     $.ajax({
    //         url: deleteUrl,
    //         method: "POST",
    //         data: {
    //             _token: csrfToken
    //         },
    //         success: function (response) {
    //             console.log(response);

    //             if (response.success) {
    //                 $('#alert_message').fadeOut('slow');
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             alert('An error occurred: ' + error);
    //         }
    //     });
    // }, 2000);

    // Form submission with AJAX
    $('#addPost').on('submit', function (e) {

        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Publishing...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: '/saveData',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showAlert('success', 'Post published successfully!');

                if (response.reset_form) {
                    form[0].reset();
                    $('#imagePreview').hide();
                    $('#titleCount').text('0');
                    $('#descCount').text('0');
                }
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                var errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '\n';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showAlert('error', errorMessage);
            },
            complete: function () {

                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });



    $('#upload').on('change', function (e) {
        var file = e.target.files[0];
        if (file) {
            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('error', 'File size must be less than 5MB');
                $(this).val('');
                return;
            }

            var validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showAlert('error', 'Only JPG, PNG, and GIF files are allowed');
                $(this).val('');
                return;
            }
        }
    });

    $('#postTitle').on('input', function () {
        var count = $(this).val().length;
        $('#titleCount').text(count);

        if (count > 200) {
            $(this).val($(this).val().substring(0, 200));
            $('#titleCount').text(200);
            showAlert('error', 'Title cannot exceed 50 characters');
        }
    });

    var tocTimeout;
    $('#description').on('input', function () {
        $('#descCount').text($(this).val().length);
        clearTimeout(tocTimeout);
        tocTimeout = setTimeout(function () {
            if ($('#tableOfContents').val()) {
                generateTOC();
            }
        }, 1000);
    });

     $('#description').on('blur', function() {
        var content = $(this).val();
        var lines = content.split('\n');
        var hasHeadingFormatIssues = false;
        var issues = [];
        
        $.each(lines, function(index, line) {
            line = $.trim(line);
            
            if (line.match(/^##[^#\s]/) || line.match(/^###[^#\s]/) || line.match(/^####[^#\s]/)) {
                hasHeadingFormatIssues = true;
                issues.push(line);
            }
        });
        
        if (hasHeadingFormatIssues && issues.length > 0) {
            if (confirm('Found ' + issues.length + ' headings without spaces after ##. Auto-fix?\n\nExample: "' + issues[0] + '"')) {
                
                var fixedContent = content;
                fixedContent = fixedContent.replace(/^##([^#\s])/gm, '## $1');
                fixedContent = fixedContent.replace(/^###([^#\s])/gm, '### $1');
                fixedContent = fixedContent.replace(/^####([^#\s])/gm, '#### $1');
                $(this).val(fixedContent);
                
                if ($('#tableOfContents').val()) {
                    generateTOC();
                }
            }
        }
    });

    $('button:contains("Save Draft")').on('click', function (e) {
        e.preventDefault();

        var form = $('form[action="/saveData"]');
        var formData = new FormData(form[0]);
        formData.append('draft', true);
        formData.append('status', 'draft');

        var draftBtn = $(this);
        var originalText = draftBtn.html();
        draftBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        draftBtn.prop('disabled', true);

        $.ajax({
            url: '/saveData',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showAlert('success', 'Draft saved successfully!');
                if (response.drafts) {
                    $('.stats-card:nth-child(2) .stats-number').text(response.drafts);
                }
            },
            error: function (xhr) {
                showAlert('error', 'Failed to save draft');
            },
            complete: function () {
                draftBtn.html(originalText);
                draftBtn.prop('disabled', false);
            }
        });
    });

    $('button:contains("Preview")').on('click', function (e) {
        e.preventDefault();

        var title = $('#postTitle').val() || 'Untitled Draft';
        var content = $('#description').val() || '<p>No content yet.</p>';
        var previewWindow = window.open('', '_blank');

        if (!previewWindow) {
            showAlert('error', 'Popup blocked. Please allow popups for preview.');
            return;
        }

        previewWindow.document.write(`
            <html>
                <head>
                    <title>Preview - ${title}</title>
                    <style>
                        body { font-family: Arial, sans-serif; max-width: 900px; margin: 32px auto; padding: 0 16px; line-height: 1.7; }
                        h1 { margin-bottom: 16px; }
                        .meta { color: #666; margin-bottom: 24px; }
                    </style>
                </head>
                <body>
                    <h1>${title}</h1>
                    <div class="meta">Preview mode (not published)</div>
                    <div>${content}</div>
                </body>
            </html>
        `);
        previewWindow.document.close();
    });

    $('#postTitle, #postSlug, #description, input[name="meta_title"], textarea[name="meta_description"]').on('input', updateCounters);

    updateCounters();

    $('#generateSlugBtn').on('click', function () {
        var title = $('#postTitle').val();
        if (title) {
            var slug = title.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            $('#postSlug').val(slug);
            $('#slugCount').text(slug.length);
        }
    });

    $('#postTitle').on('blur', function () {
        if ($(this).val().trim() && !$('#postSlug').val()) {
            $('#generateSlugBtn').click();
        }
    });

    $('#cancelBtn').click(function () {
        if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
            window.location.href = '/admin';
        }
    });

    $('#postTitle').on('input', function () {
        var title = $(this).val().trim();
        var metaTitle = $('input[name="meta_title"]');

        if (title && !metaTitle.val()) {
            metaTitle.val(title.substring(0, 60));
            updateCounters();
        }
    });

    $('#description').on('input', function () {
        var desc = $(this).val().trim();
        var metaDesc = $('textarea[name="meta_description"]');

        if (desc && !metaDesc.val()) {
            metaDesc.val(desc.substring(0, 160));
            updateCounters();
        }
    });

    $('#upload').on('change', function (e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    // edit page button
    console.log("edit js file work");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('submit', '#editForm', function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var uploadInput = document.getElementById('upload');

        // Ensure selected file is always present in multipart payload.
        if (uploadInput && uploadInput.files && uploadInput.files.length > 0) {
            formData.set('file', uploadInput.files[0]);
        }

        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: '/update_data/' + form.data('id'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showAlert('success', 'Post Update successfully!');

                if (response.reset_form) {
                    form[0].reset();
                    $('#newImagesContainer').hide();
                    $('#titleCount').text('0');
                    $('#descCount').text('0');
                }
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                var errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '\n';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showAlert('error', errorMessage);
            },
            complete: function () {

                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });

    $('#generateTOCBtn').on('click', generateTOC);
    $('#resetTOCBtn').on('click', resetTOC);

    // Edit page event 
    $('#viewTOCBtn').on('click', function () {
        var existingTOC = $('#tableOfContents').val();
        if (existingTOC) {
            try {
                var tocData = JSON.parse(existingTOC);
                if (tocData.length > 0) {
                    alert('Current TOC has ' + tocData.length + ' sections:\n\n' +
                        tocData.map(function (item, index) {
                            return (index + 1) + '. ' + item.title;
                        }).join('\n'));
                } else {
                    alert('TOC exists but is empty. Click "Regenerate TOC".');
                }
            } catch (e) {
                alert('Invalid TOC format. Click "Regenerate TOC".');
            }
        } else {
            alert('No TOC generated yet. Click "Regenerate TOC".');
        }
    });
});
