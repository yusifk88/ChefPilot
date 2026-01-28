import posthog from 'posthog-js'
import { Capacitor } from '@capacitor/core'
import { App } from '@capacitor/app'

export function initPostHog() {
    posthog.init(
        'phc_VsDac0HDtvMHYqhQij82yeT2Am1RCWP1cVp3IaJeKQY',
        {
            api_host: 'https://eu.i.posthog.com',
            autocapture: true,
            capture_pageview: false,
            capture_pageleave: false,
            persistence: 'localStorage',
            disable_session_recording: false,
        }
    )

    posthog.register({
        platform: Capacitor.getPlatform(), // ios | android | web
        is_native: Capacitor.isNativePlatform(),
    })
}