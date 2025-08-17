<div class="mx-auto max-w-5xl p-4 md:p-6" 
     x-data="{ copy(t){navigator.clipboard.writeText(t)} }">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Contact Message</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Focused view with essentials only.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.contact-us.edit', $contactUs) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                ✏️ Edit
            </a>
            <a href="{{ route('admin.contact-us.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700">
                ← Back
            </a>
        </div>
    </div>

    {{-- Card --}}
    <section class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
        {{-- Meta --}}
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if($contactUs->is_read->value)
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Read
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                        <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                        Unread
                    </span>
                @endif
            </div>
            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                Received: <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $contactUs->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>

        {{-- Details --}}
        <dl class="grid gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Name</dt>
                <dd class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $contactUs->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Email</dt>
                <dd class="mt-1 flex items-center gap-2">
                    <a href="mailto:{{ $contactUs->email }}" class="text-zinc-900 hover:underline dark:text-zinc-100">{{ $contactUs->email }}</a>
                    <button @click="copy('{{ $contactUs->email }}')" class="rounded p-1 text-xs text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 dark:hover:bg-zinc-700 dark:hover:text-zinc-200">📋</button>
                </dd>
            </div>
            @if($contactUs->phone)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Phone</dt>
                    <dd class="mt-1 flex items-center gap-2">
                        <a href="tel:{{ $contactUs->phone }}" class="text-zinc-900 dark:text-zinc-100">{{ $contactUs->phone }}</a>
                        <button @click="copy('{{ $contactUs->phone }}')" class="rounded p-1 text-xs text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 dark:hover:bg-zinc-700 dark:hover:text-zinc-200">📋</button>
                    </dd>
                </div>
            @endif
            @if(!empty($contactUs->subject))
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Subject</dt>
                    <dd class="mt-1 text-zinc-900 dark:text-zinc-100">{{ $contactUs->subject }}</dd>
                </div>
            @endif
        </dl>

        {{-- Message --}}
        <div class="mt-6">
            <h3 class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Message</h3>
            <div class="whitespace-pre-wrap rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">
                {{ $contactUs->message }}
            </div>
        </div>
    </section>
</div>
