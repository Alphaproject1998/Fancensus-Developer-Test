<x-modal name="new-data" focusable>
    <form id="NewExchangeRates" method="post" action="{{ route('exchangeRates.update') }}" enctype="multipart/form-data" class="p-6" onsubmit="return Add_XML_System.validateNewExchangeRatesForm();">
    @csrf

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
            Provide XML data for new rates
        </h2>

        <div>
            <div class="py-2">
                <div class="flex justify-between">
                    <div>
                        <x-secondary-button onclick="Add_XML_System._.File_El.click();">
                            {{ __('Choose XML File ') }} <i class="fa-solid fa-right-left"></i>
                        </x-secondary-button>
                        <input id='XML_File' hidden type='file' name='XML_File' onChange='Add_XML_System.getFileName(event);'>
                        <span id='outputFile' class="ml-2 text-gray-800 dark:text-gray-200"></span>

                    </div>
                    <div>
                        <x-secondary-button id="Reset_XML_File" class="hidden" onclick="Add_XML_System.resetFile()">
                            {{ __('Remove File ') }} <i class="fa-solid fa-x"></i>
                        </x-secondary-button>
                    </div>
                </div>
                <span id='XML_File_Error' class="XML-error mt-2 text-red-600"></span>
            </div>

            <div class="py-2 text-gray-800 dark:text-gray-200">
                <span>Or</span>
            </div>

            <div class="py-2">
                <x-input-label for="XML_URL" :value="__('XML URL')" />
                <x-text-input id="XML_URL" class="block mt-1 w-full" type="text" name="XML_URL" :value="old('XML_URL')" />
                <span id='XML_URL_Error' class="XML-error mt-2 text-red-600"></span>
            </div>
        </div>
        <div class="flex justify-end">
            <span id='New_XML_Error' class="XML-error text-red-600"></span>
        </div>
        <div class="flex justify-end pt-4">
            <x-primary-button>
                {{ __('Submit ') }} <i class="fa-solid fa-upload"></i>
            </x-primary-button>
        </div>
    </form>
</x-modal>

<script>
    const Add_XML_System = {
        _ : {
            URL_El : $("#XML_URL"),
            File_El : $("#XML_File"),
            outputFile_El : $("#outputFile"),
            Reset_XML_File_El : $("#Reset_XML_File"),
            ErrorEls : $(".XML-error"),

            ErrorData : {
                File : {
                    ID : `XML_File_Error`,
                    Text : `Please choose an XML file`,
                },
                URL : {
                    ID : `XML_URL_Error`,
                    Text : `Please make sure your url is xml data`,
                },
                XML : {
                    ID : `New_XML_Error`,
                    Text : `Please either choose a file or add a URL`,
                },
            },
        },

        resetFile() {
            Add_XML_System._.File_El.val(null);
            Add_XML_System._.outputFile_El.text(null);
            Add_XML_System._.Reset_XML_File_El.addClass("hidden");
        },
        getFileName(event) {
            if (!event || !event.target || !event.target.files || event.target.files.length === 0) {
                return;
            }

            const name = event.target.files[0].name;
            Add_XML_System._.outputFile_El.text(name);
            Add_XML_System._.Reset_XML_File_El.removeClass("hidden");
        },
        validateNewExchangeRatesForm() {
            let name = '';
            let ErrorType = `XML`;

            Add_XML_System._.ErrorEls.text(null);

            if(Add_XML_System._.outputFile_El.text() !== "") {
                name = Add_XML_System._.outputFile_El.text();
                ErrorType = `File`;
            } else {
                if(Add_XML_System._.URL_El.val() !== "") {
                    name = Add_XML_System._.URL_El.val();
                    ErrorType = `URL`;
                }
            }

            let isXML = name.split('.').pop().toLowerCase() === "xml";

            if(!isXML)
            {
                let ErrorData = Add_XML_System._.ErrorData[ErrorType];
                $(`#${ErrorData.ID}`).text(ErrorData.Text);
                return false;
            }

            return true;
        }
    }
</script>
