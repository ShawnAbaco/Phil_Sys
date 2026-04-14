@extends('layouts.admin')

@section('title', 'User Details')
@section('pageTitle', 'User Profile')
@section('pageSubtitle', 'View user information and activity')

@php $activeMenu = 'users'; @endphp

@section('content')
    <div class="profile-container">
        <div class="profile-header-card">
            <div class="profile-cover">
                <div class="profile-avatar-large">
                    {{ substr($user->name ?? ($user->full_name ?? 'U'), 0, 2) }}
                </div>
            </div>
            <div class="profile-info">
                <h2>{{ $user->name ?? $user->full_name }}</h2>
                <p class="profile-designation">{{ $user->designation ?? 'User' }}</p>
                <div class="profile-meta">
                    <span class="profile-meta-item">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        {{ $user->email }}
                    </span>
                    <span class="profile-meta-item">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $user->window_num ? 'Window ' . $user->window_num : 'No window assigned' }}
                    </span>
                    <span class="profile-meta-item">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        Member since {{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="profile-actions">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>

        <div class="profile-content-grid">
            <div class="profile-card">
                <h3>Personal Information</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value">{{ $user->name ?? $user->full_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Username:</span>
                        <span class="info-value">{{ $user->username }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Contact Number:</span>
                        <span class="info-value">{{ $user->contact_number ?? 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Birthdate:</span>
                        <span
                            class="info-value">{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('F d, Y') : 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Gender:</span>
                        <span class="info-value">{{ ucfirst($user->gender ?? 'Not specified') }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h3>Account Details</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Designation:</span>
                        <span class="info-value">{{ $user->designation ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Window Number:</span>
                        <span
                            class="info-value">{{ $user->window_num ? 'Window ' . $user->window_num : 'Not assigned' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            @php
                                $status = $user->status ?? 'active';
                                $isActive = $status === 'active';
                            @endphp
                            <span class="status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                <span class="status-dot"></span>
                                {{ $isActive ? 'Active' : ucfirst($status) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Login:</span>
                        <span
                            class="info-value">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last IP:</span>
                        <span class="info-value">{{ $user->last_login_ip ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Created:</span>
                        <span
                            class="info-value">{{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-card full-width">
                <h3>Recent Activity</h3>
                <div class="timeline">
                    @forelse($activities ?? [] as $activity)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="timeline-content">
                                <p>{{ $activity->description }}</p>
                                <small>{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="stats-grid-small">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon" style="background: rgba(0, 56, 168, 0.1);">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <div>
                        <span class="stat-mini-label">Total Served</span>
                        <span class="stat-mini-value">{{ $totalServed ?? 0 }}</span>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon" style="background: rgba(252, 209, 22, 0.15);">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <span class="stat-mini-label">Today's Served</span>
                        <span class="stat-mini-value">{{ $todayServed ?? 0 }}</span>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon" style="background: #10b981; color: white;">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <span class="stat-mini-label">Avg. Service Time</span>
                        <span class="stat-mini-value">{{ $avgServiceTime ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
