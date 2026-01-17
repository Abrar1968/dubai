# Walkthrough: Fixing Views Loading & Hajj Section

**Date:** January 17, 2026  
**Status:** ✅ Completed

## Overview

This walkthrough documents the investigation and resolution of the issues where the application views were not loading and the subsequent audit and repair of the Hajj & Umrah section functionality.

## 1. Issue: Views Not Loading (Blank Screen)

### Diagnosis

- The specific symptom was a blank whitescreen on `http://127.0.0.1:8000`.
- Browser console showed `net::ERR_CONNECTION_REFUSED` for Vite assets (`[::1]:5173`).
- **Root Cause:** The Vite development server was crashing immediately after startup due to a **Rust UTF-8 Error** (`Utf8Error`).
- **Source:** Corrupted/Non-ASCII characters (specifically broken emoji bytes) were found in `resources/js/Pages/hajj&umrah/contactus.vue`.

### Fix

- **Action:** Identified and removed the corrupted characters from `contactus.vue`.
- **Action:** Configured `vite.config.ts` to explicitly use `host: '127.0.0.1'` for better stability on Windows.
- **Result:** Vite server started successfully, and the main pages loaded correctly.

## 2. Issue: Hajj Section Audit & Fixes

After the pages loaded, a comprehensive audit of the Hajj section revealed 3 critical functional issues.

### Fix 1: Articles Page Compilation Error

- **Problem:** accessible `/articles` threw a `Unicode escape sequence` error.
- **Root Cause:** Similar encoding corruption in `resources/js/Pages/hajj&umrah/articles.vue`.
- **Fix:** Cleaned the file of non-ASCII characters.

### Fix 2: Broken Package Details (404)

- **Problem:** Clicking "View Details" on any package led to a 404 error.
- **Root Cause:** The component `package_detail.vue` was referenced in the controller but did not exist in the filesystem.
- **Fix:** Created `resources/js/Pages/hajj&umrah/package_detail.vue` with a complete design including:
    - Hero header with package info
    - Itinerary timeline
    - Inclusions/Exclusions lists
    - Sticky booking card

### Fix 3: Related Articles Navigation

- **Problem:** "Read More" links in the "Related Articles" section did not work.
- **Root Cause:** The `article_detail.vue` component used static `const` for article data, so it didn't react when Inertia loaded new data for the same component.
- **Fix:** Refactored the component to use `computed` properties for `displayArticle` and `displayRelatedArticles`.

## 3. Verification Results

| Feature               | Pre-Fix Status       | Post-Fix Status            |
| --------------------- | -------------------- | -------------------------- |
| Main Page Load        | ❌ Blank Screen      | ✅ Loaded                  |
| Hajj Section Load     | ❌ Blank Screen      | ✅ Loaded                  |
| Articles Listing      | ❌ Compilation Error | ✅ Working                 |
| Package Details       | ❌ 404 Not Found     | ✅ Working (New Component) |
| Related Article Links | ❌ Non-responsive    | ✅ Working                 |
