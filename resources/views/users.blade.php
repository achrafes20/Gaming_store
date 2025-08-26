@extends('Layouts.master')
@section('content')
    <div class="cyber-hero-section">
        <div class="cyber-hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="cyber-hero-text">
                        <h1 class="cyber-title">MANAGE <span class="cyber-accent">USERS</span></h1>
                        <div class="cyber-pulse-animation">
                            <div class="pulse-circle"></div>
                            <div class="pulse-circle delay-1"></div>
                            <div class="pulse-circle delay-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="cyber-users-section">
        <div class="container">
            <div class="cyber-accordion-wrap">
                <div class="cyber-accordion" id="cyberAccordion">
                    @foreach ($users as $user)
                        <div class="cyber-user-accordion" data-aos="fade-up">
                            <div class="cyber-user-header" id="cyberHeading{{ $user->id }}">
                                <button class="cyber-accordion-btn" type="button" data-toggle="collapse"
                                    data-target="#cyberCollapse{{ $user->id }}" aria-expanded="true"
                                    aria-controls="cyberCollapse{{ $user->id }}">
                                    <span class="cyber-user-name">{{ $user->name }}</span>
                                    <span
                                        class="cyber-user-role {{ $user->role === 'admin' ? 'admin-role' : 'client-role' }}">
                                        {{ $user->role === 'admin' ? 'admin' : 'client' }}
                                    </span>

                                </button>
                            </div>

                            <div id="cyberCollapse{{ $user->id }}" class="cyber-user-collapse show"
                                aria-labelledby="cyberHeading{{ $user->id }}" data-parent="#cyberAccordion">
                                <div class="cyber-user-body">
                                    <div class="cyber-user-details">
                                        <div class="cyber-form-row">
                                            <div class="cyber-form-group">
                                                <div class="cyber-input-container">
                                                    <input type="text" value="{{ $user->name }}" class="cyber-input"
                                                        readonly>
                                                    <div class="cyber-input-border"></div>
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="cyber-form-group">
                                                <div class="cyber-input-container">
                                                    <input type="text" value="{{ $user->email }}" class="cyber-input"
                                                        readonly>
                                                    <div class="cyber-input-border"></div>
                                                    <div class="cyber-input-icon">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cyber-form-actions">
                                            @if ($user->role == 'admin')
                                                <form action="/Users_client/{{ $user->id }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="cyber-btn warning-btn">
                                                        <i class="fas fa-user-shield"></i>
                                                        <span>DEMOTE TO CLIENT</span>
                                                        <div class="cyber-btn-hover"></div>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="/Users_admin/{{ $user->id }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="cyber-btn success-btn">
                                                        <i class="fas fa-user-crown"></i>
                                                        <span>PROMOTE TO ADMIN</span>
                                                        <div class="cyber-btn-hover"></div>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <div class="cyber-floating-elements">
        <div class="cyber-orb orb-1"></div>
        <div class="cyber-orb orb-2"></div>
        <div class="cyber-orb orb-3"></div>
        <div class="cyber-circuit-line"></div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/users.css') }}">
    @endpush

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap"
            rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <script src="{{ asset('assets/js/users.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif
    @endpush
@endsection
