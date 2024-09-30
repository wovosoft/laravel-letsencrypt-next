<div class="bg-white p-5 mt-5 shadow-2xl">
    @if($this->showFirstStep)
        <h2 class="text-4xl font-extrabold dark:text-white">
            Provide Domain Information
        </h2>
        <form wire:submit="handleFirstStep" class="mt-5">
            <div>
                <label for="domain_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Enter domain name(s)*
                </label>
                <input type="text" id="domain_name"
                       wire:model="form.domain"
                       name="domain"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="example.com" required
                />
            </div>
            <div class="mt-5">
                <label for="email_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Email Address *
                </label>
                <input type="email" id="email_address"
                       wire:model="form.email"
                       name="email"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="mail@example.com"
                       required
                />
            </div>

            <div class="mt-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Verification Type
                </label>
                <div class="flex">
                    @foreach($verificationMethods as $title=>$value)
                        <div class="flex items-center me-4">
                            <input id="inline-radio-{{$value}}" type="radio" value="{{$value}}"
                                   wire:model="form.type"
                                   name="inline-radio-group"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-radio-{{$value}}"
                                   class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{$title}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">

                <div role="status" class="inline-block" wire:loading>
                    <svg aria-hidden="true"
                         class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor"/>
                        <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>

                Request SSL
            </button>
        </form>

    @elseif($this->showSecondStep)
        <div class="p-5">
            <h2 class="text-4xl font-extrabold dark:text-white">
                Let's verify that you own:
            </h2>
            <h4 class="text-2xl font-extrabold dark:text-white">
                {{$this->form->domain}}
            </h4>
            <ol class="list-decimal mt-5">
                <li>
                    <p>Download below file(s):</p>
                    <button type="button"
                            wire:click="downloadHttpAuthorizationFile('{{$this->httpVerificationFile}}')"
                            class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                        Download File
                    </button>

                </li>
                <li>
                    Create a folder <code class="text-red-600">".well-known"</code> in the root folder of your
                    domain. And inside the <code class="text-red-600">".well-known"</code>
                    create another folder <code class="text-red-600">"acme-challenge"</code>.
                    Then upload the above file(s) inside the acme-challenge folder.
                </li>
                <li>
                    Click on the below link(s) and check that it opens a page with random characters on your
                    domain.
                    Like this:
                    <ul class="list-disc">
                        <li>
                            <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                               href="http://{{$this->form->domain}}/.well-known/acme-challenge/{{$this->httpVerificationFile}}">
                                http://{{$this->form->domain}}
                                /.well-known/acme-challenge/{{$this->httpVerificationFile}}
                            </a>
                        </li>
                    </ul>

                <li>
                    Click on the button and Let's Encrypt will verify that you own the domain and issue the SSL
                    Certificate (This might take few minutes)
                    <form wire:submit="verifyConfigurationAndGenerateCertificates">
                        <button type="submit"
                                class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">

                            <div role="status" class="inline-block" wire:loading>
                                <svg aria-hidden="true"
                                     class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                     viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="currentColor"/>
                                    <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>

                            Verify and Generate Certificates
                        </button>
                    </form>
                </li>
            </ol>
        </div>
    @elseif($this->showThirdStep)
        <div class="p-5">
            @foreach($this->certificates as $certificateName=>$certificateContent)
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{$certificateName}}
                </label>
                <textarea rows="4"
                          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="Write your thoughts here...">
                    {{$certificateContent}}
                </textarea>
            @endforeach
        </div>
    @endif
</div>
