<div class="container m-auto mt-5">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    SL
                </th>
                <th scope="col" class="px-6 py-3">
                    Domain
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                <th scope="col" class="px-6 py-3">
                    Verification File Upload URL
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var \Illuminate\Pagination\LengthAwarePaginator $domains */
            ?>
            @foreach($domains as $domain)
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-2 max-w-[30px]">
                        {{$loop->index+$domains->firstItem()}}
                    </th>
                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$domain->domain}}
                    </td>
                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$domain->email}}
                    </td>
                    <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$domain->verification_file_upload_url}}
                    </td>
                    <td class="px-6 py-2">
                        <a href="{{route('domains.show',['domain'=>$domain->id])}}"
                           wire:navigate
                           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            View
                        </a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-2">
            {{$domains->links()}}
        </div>
    </div>
</div>
