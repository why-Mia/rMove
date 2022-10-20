<!-- Modal overlay -->
<div id="modal-overlay" class="modal-overlay fixed hidden inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full"></div>

<!-- Main modal -->
<div id="roblox-account-linking-modal" tabindex="-1" class="modal overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center flex hidden" role="dialog">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-main-650">
            <!-- Modal header -->
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Account Linking
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="roblox-account-linking-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="check-username-form" method="POST" action="{{route('settings.refresh_code')}}">
                @method('PUT')
                @csrf
            <div class="p-6">
                <span class="username-error-text dark:text-red-400 text-red-700 mb-4"></span>
                <p class="text-base leading-relaxed text-gray-900 dark:text-gray-100">
                    To get started, enter your Roblox Username:
                </p>
                <x-input id="roblox_username" class="block w-full my-5" type="text" name="roblox_username" autocomplete="username" />
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    (NOT your display name)
                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                <button data-modal-toggle="roblox-account-linking-modal" type="button" class="toggle-visibility text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-main-650 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-main-500 dark:focus:ring-gray-600">Cancel</button>
                <button id="roblox-username-confirm-button" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Continue</button>
            </div>
            </form>
            <form id="verify-user-form" class="hidden" method="POST" action="">
                @method('PUT')
                @csrf
            <div class="p-6 space-y-2">
                <span class="username-error-text dark:text-red-400 text-red-700 mb-4"></span>
                <p class="text-base leading-relaxed text-gray-900 dark:text-gray-100">
                    To complete the account linking process, follow the steps below:
                        <ul class="text-gray-900 dark:text-gray-100 list-decimal list-inside">
                            <li>Go to your <a id="roblox-profile-link" href="#" class="dark:text-blue-400 text-blue-900 underline" target="_blank" rel="noopener noreferrer">Roblox profile</a>.</li>
                            <li>Edit your <strong>About</strong> section by clicking the button shown below
                            <img src="/img/about-edit-example.jpg" class="ml-4 mb-3 mt-1"></li>
                            <li>Copy the following text into to your About section:</li>
                            <div class="flex mt-2 mb-1">
                                <x-input id="verification-code" class="block w-full rounded-r-none text-black" type="text" name="verification_code" value="If you see this text, things did a break" autofocus readonly />
                                <span id="roblox-refresh-code-button" class="btn-dark rounded-l-none border disabled:opacity-25 uppercase tracking-wider whitespace-nowrap">New code</span>
                            </div>
                            <span class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                This can be removed after your account has been verified.
                            </span>
                            <li class="mt-3"><strong>Save</strong> and check that the code isn't censored. If it is, press <strong>New Code</strong> and repeat.</li>
                        </ul>
                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-between items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                <div>
                    <button type="button" data-click-target="#verify-user-form,#check-username-form" class="toggle-visibility text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-main-650 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-main-500 dark:focus:ring-gray-600">Back</button>
                </div>
                <div class="flex nowrap space-x-2">
                <button data-modal-toggle="roblox-account-linking-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-main-650 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-main-500 dark:focus:ring-gray-600">Cancel</button>
                <button id="complete-verification-button" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Continue</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
