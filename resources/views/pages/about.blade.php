<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Unik Muse</title>
    <meta name="description" content="Learn more about Unik Muse, our mission, and the ideas behind our content.">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-unik-light text-unik-dark font-sans">
    @include('common.header')

    <!-- About Hero Section -->
    <section class="py-12 md:py-16 lg:py-20">
        <div class="container-unik text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-unik-dark mb-4 font-serif">
                About Unik Muse
            </h1>
            <p class="text-lg md:text-xl text-unik-muted max-w-3xl mx-auto">
                Welcome to a space where
                <span class="text-unik-primary font-semibold">unique perspectives</span>
                meet
                <span class="text-unik-primary font-semibold">inspiring ideas</span>.
                We believe every thought has the power to spark something extraordinary.
            </p>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-8 md:py-12 lg:py-16">
        <div class="container-unik">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Our Story -->
                <div class="bg-white rounded-unik-lg shadow-unik-md p-6 md:p-8 border border-unik-border">
                    <h2
                        class="text-2xl md:text-3xl font-bold text-unik-dark mb-6 font-serif relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-16 after:h-1 after:bg-unik-primary">
                        Our Story
                    </h2>
                    <div class="space-y-4 text-unik-muted">
                        <p class="text-base md:text-lg">
                            Unik Muse began as a simple idea in 2020: to create a digital sanctuary for curious minds.
                            What started as a personal blog has grown into a community of thinkers, creators, and
                            lifelong learners.
                        </p>
                        <p class="text-base md:text-lg">
                            Our name reflects our mission—<strong class="text-unik-dark">"Unik"</strong> for the
                            distinctive perspectives we share, and
                            <strong class="text-unik-dark">"Muse"</strong> for the inspiration we aim to provide.
                            We believe that in a world of endless information, what's often missing is the unique angle,
                            the fresh take, the thoughtful pause.
                        </p>
                        <p class="text-base md:text-lg">
                            Today, we publish weekly articles exploring everything from creativity and technology to
                            mindfulness
                            and personal growth. Each piece is carefully crafted to not just inform, but to inspire
                            action and reflection.
                        </p>
                    </div>
                </div>

                <!-- What We Believe -->
                <div class="bg-white rounded-unik-lg shadow-unik-md p-6 md:p-8 border border-unik-border">
                    <h2
                        class="text-2xl md:text-3xl font-bold text-unik-dark mb-6 font-serif relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-16 after:h-1 after:bg-unik-primary">
                        What We Believe
                    </h2>
                    <div class="space-y-4 text-unik-muted">
                        <p class="text-base md:text-lg">
                            We operate on a few core principles that guide everything we publish:
                        </p>
                        <div class="space-y-3">
                            <p class="text-base md:text-lg">
                                <strong class="text-unik-dark">Depth over breadth:</strong>
                                We'd rather explore one idea thoroughly than skim ten. Our articles are researched,
                                thoughtful, and substantive.
                            </p>
                            <p class="text-base md:text-lg">
                                <strong class="text-unik-dark">Quality over quantity:</strong>
                                We publish less frequently than many blogs, but each article receives careful attention
                                and multiple rounds of editing.
                            </p>
                            <p class="text-base md:text-lg">
                                <strong class="text-unik-dark">Conversation over monologue:</strong>
                                We see our articles as starting points for discussion, not final pronouncements.
                                Your perspective in the comments is valued.
                            </p>
                            <p class="text-base md:text-lg">
                                <strong class="text-unik-dark">Practical inspiration:</strong>
                                We aim to provide ideas you can actually use, not just admire.
                                Each article includes actionable takeaways.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-8 md:py-12 lg:py-16">
        <div class="container-unik">
            <div class="bg-white rounded-unik-lg shadow-unik-md p-8 md:p-12 border border-unik-border">
                <h2 class="text-2xl md:text-3xl font-bold text-unik-dark mb-8 text-center font-serif">
                    Our Mission
                </h2>
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                    <div class="text-unik-primary text-6xl lg:text-8xl flex-shrink-0">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="space-y-4 text-unik-muted">
                        <p class="text-base md:text-lg">
                            To create a digital space that nurtures intellectual curiosity and inspires meaningful
                            action.
                            We aim to bridge the gap between information and insight, offering content that doesn't just
                            fill time but enriches it.
                        </p>
                        <p class="text-base md:text-lg">
                            In a world of clickbait and superficial content, we're building an oasis of thoughtful
                            discourse.
                            We want our readers to leave each article not just knowing something new, but seeing
                            something differently.
                        </p>
                        <p class="text-base md:text-lg">
                            Our commitment is to produce content that stands the test of time—articles you might
                            bookmark,
                            share with friends, or return to months later when you need that perspective again.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-8 md:py-12 lg:py-16">
        <div class="container-unik">
            <h2 class="text-2xl md:text-3xl font-bold text-unik-dark mb-8 md:mb-12 text-center font-serif">
                Our Core Values
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Value Card 1 -->
                <div
                    class="bg-white rounded-unik-lg shadow-unik-md p-6 border border-unik-border hover:shadow-unik-lg hover:-translate-y-1 transition-all duration-300 text-center">
                    <div class="text-unik-primary text-4xl mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unik-dark mb-3">Unique Perspective</h3>
                    <p class="text-unik-muted">
                        We seek out angles and insights you won't find elsewhere, valuing original thought over echo
                        chambers.
                    </p>
                </div>

                <!-- Value Card 2 -->
                <div
                    class="bg-white rounded-unik-lg shadow-unik-md p-6 border border-unik-border hover:shadow-unik-lg hover:-translate-y-1 transition-all duration-300 text-center">
                    <div class="text-unik-primary text-4xl mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unik-dark mb-3">Authenticity</h3>
                    <p class="text-unik-muted">
                        We write with genuine voice and conviction, sharing real experiences and honest reflections.
                    </p>
                </div>

                <!-- Value Card 3 -->
                <div
                    class="bg-white rounded-unik-lg shadow-unik-md p-6 border border-unik-border hover:shadow-unik-lg hover:-translate-y-1 transition-all duration-300 text-center">
                    <div class="text-unik-primary text-4xl mb-4">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unik-dark mb-3">Community</h3>
                    <p class="text-unik-muted">
                        We believe ideas grow best in conversation. Our readers are collaborators in this journey.
                    </p>
                </div>

                <!-- Value Card 4 -->
                <div
                    class="bg-white rounded-unik-lg shadow-unik-md p-6 border border-unik-border hover:shadow-unik-lg hover:-translate-y-1 transition-all duration-300 text-center">
                    <div class="text-unik-primary text-4xl mb-4">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unik-dark mb-3">Growth Mindset</h3>
                    <p class="text-unik-muted">
                        We're committed to learning, evolving, and improving—both in our content and as individuals.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Author Section (Commented Out) -->
    {{--
    <section class="py-8 md:py-12 lg:py-16 bg-gradient-to-br from-unik-light via-white to-unik-light/50">
        <div class="container-unik">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80"
                     alt="Alex Morgan"
                     class="w-48 h-48 md:w-56 md:h-56 rounded-full border-4 border-white shadow-unik-lg object-cover">
                <div class="flex-1">
                    <h2 class="text-2xl md:text-3xl font-bold text-unik-dark mb-4 font-serif">Meet the Founder</h2>
                    <div class="space-y-4 text-unik-muted">
                        <p class="text-base md:text-lg">
                            <strong class="text-unik-dark">Alex Morgan</strong> started Unik Muse after a decade working in content creation and digital media. 
                            Frustrated by the shallow nature of much online content, Alex wanted to build a space for substantive, inspiring writing.
                        </p>
                        <p class="text-base md:text-lg">
                            When not writing or editing for Unik Muse, Alex can be found hiking, reading philosophy, or 
                            experimenting with new forms of digital storytelling. Alex believes that the best ideas often come 
                            from unexpected connections between seemingly unrelated fields.
                        </p>
                        <p class="text-base md:text-lg italic">
                            "My goal with Unik Muse is simple: to create content that makes people pause, think, and see their world a little differently."
                        </p>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-unik-dark text-white rounded-full flex items-center justify-center hover:bg-unik-primary transition-colors">
                            <i class="fab fa-medium"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    --}}

    <!-- CTA Section -->
    <section class="py-8 md:py-12 lg:py-16">
        <div class="container-unik">
            <div class="bg-unik-primary rounded-unik-lg p-8 md:p-12 text-center text-white">
                <h2 class="text-2xl md:text-3xl font-bold mb-4 font-serif">Join Our Community</h2>
                <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-8">
                    Subscribe to our newsletter to receive weekly inspiration, exclusive content, and updates on new
                    articles directly in your inbox.
                </p>
                <a href="/subscribe.html"
                    class="inline-block bg-black text-unik-primary font-semibold px-8 py-4 rounded-unik-lg hover:-translate-y-1 transition-all duration-300">
                    Subscribe Now
                </a>
            </div>
        </div>
    </section>

    @include('common.footer')
</body>

</html>
