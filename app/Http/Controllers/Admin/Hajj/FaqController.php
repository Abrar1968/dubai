<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Services\FaqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(
        protected FaqService $faqService
    ) {}

    /**
     * Display a listing of FAQs.
     */
    public function index(Request $request): View
    {
        $section = $request->get('section', 'hajj');
        $faqs = $this->faqService->list($section);
        $counts = $this->faqService->getCountBySection();

        return view('admin.pages.faqs.index', [
            'faqs' => $faqs,
            'counts' => $counts,
            'currentSection' => $section,
        ]);
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function create(): View
    {
        return view('admin.pages.faqs.create');
    }

    /**
     * Store a newly created FAQ.
     */
    public function store(FaqRequest $request): RedirectResponse
    {
        $this->faqService->create($request->validated());

        return redirect()
            ->route('admin.hajj.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function edit(int $id): View
    {
        $faq = $this->faqService->getById($id);
        $categories = $this->faqService->getCategories();

        return view('admin.pages.faqs.edit', [
            'faq' => $faq,

        return view('admin.pages.faqs.edit', [
            'faq' => $faq
     * Update the specified FAQ.
     */
    public function update(FaqRequest $request, int $id): RedirectResponse
    {
        $faq = $this->faqService->getById($id);
        $this->faqService->update($faq, $request->validated());

        return redirect()
            ->route('admin.hajj.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified FAQ.
     */
    public function destroy(int $id): RedirectResponse
    {
        $faq = $this->faqService->getById($id);
        $this->faqService->delete($faq);

        return redirect()
            ->route('admin.hajj.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $faq = $this->faqService->getById($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();
this->faqService->toggleActive($faqrect()
            ->back()
            ->with('success', 'FAQ status updated.');
    }

    /**
     * Reorder FAQs.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->input('order', []);
        $this->faqService->reorder($order);

        return redirect()
            ->back()
            ->with('success', 'FAQs reordered successfully.');
    }
}
