<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Unik Muse</title>
    <meta name="description" content="Contact Unik Muse for feedback, collaboration, and inquiries.">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-unik-light text-unik-dark font-sans">
    @include('common.header')
    
    <!-- Contact Hero -->
    <section class="py-12 md:py-16 bg-gradient-to-br from-unik-primary/10 via-unik-light to-unik-accent/10">
        <div class="container-unik text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-unik-dark mb-4 font-serif">
                Get In Touch
            </h1>
            <p class="text-lg md:text-xl text-unik-muted max-w-3xl mx-auto">
                We'd love to hear from you! Whether you have questions, feedback, or collaboration ideas, 
                <span class="text-unik-primary font-semibold">your thoughts inspire us</span>.
            </p>
        </div>
    </section>

    <!-- Contact Container -->
    <section class="py-8 md:py-12 lg:py-16">
        <div class="container-unik">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Contact Information -->
                <div class="bg-white rounded-unik-lg shadow-unik-md p-6 md:p-8 border border-unik-border">
                    <h2 class="text-2xl md:text-3xl font-bold text-unik-dark mb-8 font-serif relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-16 after:h-1 after:bg-unik-primary">
                        Contact Information
                    </h2>

                    <!-- Location -->
                    <div class="flex items-start gap-4 md:gap-6 mb-8">
                        <div class="w-12 h-12 bg-unik-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-unik-primary text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-unik-dark mb-2">Our Location</h3>
                            <p class="text-unik-muted">666 Virtual City</p>
                            <p class="text-unik-muted">Creative District, Muse City 10001</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-4 md:gap-6 mb-8">
                        <div class="w-12 h-12 bg-unik-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-unik-primary text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-unik-dark mb-2">Email Us</h3>
                            <p class="text-unik-muted mb-1">
                                General Inquiries: <a href="mailto:hello@unikmuse.com" class="text-unik-primary hover:text-unik-secondary hover:underline transition-colors">hello@unikmuse.com</a>
                            </p>
                            <p class="text-unik-muted">
                                Collaborations: <a href="mailto:collab@unikmuse.com" class="text-unik-primary hover:text-unik-secondary hover:underline transition-colors">collab@unikmuse.com</a>
                            </p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-4 md:gap-6 mb-8">
                        <div class="w-12 h-12 bg-unik-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-unik-primary text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-semibold text-unik-dark mb-2">Call Us</h3>
                            <p class="text-unik-muted mb-1">
                                Phone: <a href="tel:+XXXXXXXXXX" class="text-unik-primary hover:text-unik-secondary hover:underline transition-colors">+91 (XXX) XXX-XXXX</a>
                            </p>
                            <p class="text-unik-muted">Hours: Monday-Friday, 9am-5pm EST</p>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="mt-8 pt-8 border-t border-unik-border">
                        <h3 class="text-lg md:text-xl font-semibold text-unik-dark mb-4">Follow Us</h3>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary hover:-translate-y-1 transition-all duration-300" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary hover:-translate-y-1 transition-all duration-300" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary hover:-translate-y-1 transition-all duration-300" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-unik-lg shadow-unik-md p-6 md:p-8 border border-unik-border">
                    <h2 class="text-2xl md:text-3xl font-bold text-unik-dark mb-8 font-serif relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-16 after:h-1 after:bg-unik-primary">
                        Send a Message
                    </h2>

                    <div id="formStatus" class="hidden mb-6 p-4 rounded-unik-md"></div>

                    <form id="contactForm" class="space-y-6">
                        <!-- Name & Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-unik-dark font-medium mb-2">
                                    Full Name *
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent transition-all duration-300"
                                       placeholder="Your Name" 
                                       required>
                            </div>

                            <div>
                                <label for="email" class="block text-unik-dark font-medium mb-2">
                                    Email Address *
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent transition-all duration-300"
                                       placeholder="your@email.com" 
                                       required>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-unik-dark font-medium mb-2">
                                Subject *
                            </label>
                            <input type="text" 
                                   id="subject" 
                                   name="subject" 
                                   class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent transition-all duration-300"
                                   placeholder="What is this regarding?" 
                                   required>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-unik-dark font-medium mb-2">
                                Category *
                            </label>
                            <select id="category" 
                                    name="category" 
                                    class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent transition-all duration-300"
                                    required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="general">General Inquiry</option>
                                <option value="feedback">Website Feedback</option>
                                <option value="collaboration">Collaboration Idea</option>
                                <option value="guest-post">Guest Post Proposal</option>
                                <option value="technical">Technical Issue</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-unik-dark font-medium mb-2">
                                Your Message *
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="6"
                                      class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent transition-all duration-300 resize-y"
                                      placeholder="What would you like to share with us?" 
                                      required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-unik-primary text-white font-semibold py-4 rounded-unik-md hover:bg-unik-primary/90 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-8 md:py-12">
        <div class="container-unik">
            <div class="bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent rounded-unik-lg shadow-unik-lg overflow-hidden h-64 md:h-80">
                <div class="w-full h-full flex flex-col items-center justify-center text-white text-center p-6">
                    <i class="fas fa-map-marked-alt text-5xl md:text-6xl mb-4 opacity-90"></i>
                    <h3 class="text-xl md:text-2xl font-bold mb-2 font-serif">Our Virtual Headquarters</h3>
                    <p class="text-white/90 max-w-2xl">
                        Unik Muse operates as a digital-first publication with contributors from around the world.
                    </p>
                    <p class="text-white/90">
                        Our main coordination hub is based in Muse City, but our ideas know no borders.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-8 md:py-12 lg:py-16">
        <div class="container-unik">
            <h2 class="text-2xl md:text-3xl font-bold text-unik-dark mb-8 md:mb-12 text-center font-serif">
                Frequently Asked Questions
            </h2>
            
            <div class="max-w-3xl mx-auto space-y-4">
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm overflow-hidden border border-unik-border">
                    <div class="faq-question cursor-pointer p-5 md:p-6 flex justify-between items-center hover:bg-unik-light/50 transition-colors">
                        <span class="text-lg font-semibold text-unik-dark">How long does it take to get a response?</span>
                        <i class="fas fa-chevron-down text-unik-primary transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer overflow-hidden max-h-0 transition-all duration-300">
                        <div class="p-5 md:p-6 pt-0 text-unik-muted">
                            <p>We strive to respond to all inquiries within 24-48 hours during weekdays. For more complex questions or collaboration proposals, it may take up to 3-5 business days as we give each idea the consideration it deserves.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm overflow-hidden border border-unik-border">
                    <div class="faq-question cursor-pointer p-5 md:p-6 flex justify-between items-center hover:bg-unik-light/50 transition-colors">
                        <span class="text-lg font-semibold text-unik-dark">Do you accept guest posts?</span>
                        <i class="fas fa-chevron-down text-unik-primary transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer overflow-hidden max-h-0 transition-all duration-300">
                        <div class="p-5 md:p-6 pt-0 text-unik-muted">
                            <p>Yes! We welcome thoughtful guest contributions that align with our mission of providing unique perspectives and inspiration. Please use the "Guest Post Proposal" category when submitting your idea through our contact form, and include links to your previous work or writing samples.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm overflow-hidden border border-unik-border">
                    <div class="faq-question cursor-pointer p-5 md:p-6 flex justify-between items-center hover:bg-unik-light/50 transition-colors">
                        <span class="text-lg font-semibold text-unik-dark">Can I suggest a topic for the blog?</span>
                        <i class="fas fa-chevron-down text-unik-primary transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer overflow-hidden max-h-0 transition-all duration-300">
                        <div class="p-5 md:p-6 pt-0 text-unik-muted">
                            <p>Absolutely! We value our readers' insights and regularly consider topic suggestions. While we can't guarantee every suggestion will become a post, we read and consider all ideas. The most successful suggestions are those that offer a unique angle on a subject rather than covering well-trodden ground.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm overflow-hidden border border-unik-border">
                    <div class="faq-question cursor-pointer p-5 md:p-6 flex justify-between items-center hover:bg-unik-light/50 transition-colors">
                        <span class="text-lg font-semibold text-unik-dark">Do you offer advertising or sponsored content?</span>
                        <i class="fas fa-chevron-down text-unik-primary transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer overflow-hidden max-h-0 transition-all duration-300">
                        <div class="p-5 md:p-6 pt-0 text-unik-muted">
                            <p>We carefully select a limited number of partnerships with brands and organizations that align with our values. All sponsored content is clearly marked as such and maintains the same quality standards as our regular posts. For partnership inquiries, please select "Collaboration Idea" in the contact form.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('common.footer')

    <script>
        // Form Submission Handler
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form elements
            const form = e.target;
            const formStatus = document.getElementById('formStatus');

            // Simple validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const category = document.getElementById('category').value;
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !subject || !category || !message) {
                showFormStatus('Please fill in all required fields.', 'error');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showFormStatus('Please enter a valid email address.', 'error');
                return;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // Show success message
                showFormStatus('Thank you for your message! We\'ll get back to you within 24-48 hours.', 'success');

                // Reset form
                form.reset();

                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });

        function showFormStatus(message, type) {
            const formStatus = document.getElementById('formStatus');
            formStatus.textContent = message;
            formStatus.className = `p-4 rounded-unik-md mb-6 ${type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'}`;
            formStatus.classList.remove('hidden');

            // Auto-hide after 5 seconds
            setTimeout(() => {
                formStatus.classList.add('hidden');
            }, 5000);
        }

        // FAQ Toggle Functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const icon = question.querySelector('i');
                
                // Toggle active class on question
                question.classList.toggle('active');
                
                // Toggle icon rotation
                icon.classList.toggle('rotate-180');
                
                // Toggle answer visibility
                if (answer.style.maxHeight) {
                    answer.style.maxHeight = null;
                } else {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });

        // Add focus animations to form inputs
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', '-translate-y-1');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', '-translate-y-1');
            });
        });
    </script>
</body>
</html>
