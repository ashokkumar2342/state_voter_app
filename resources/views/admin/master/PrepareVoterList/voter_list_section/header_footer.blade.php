@page h{{ $mainpagedetails[0]->ward }} {
        odd-header-name: html_ph{{ $mainpagedetails[0]->ward }};
        even-header-name: html_ph{{ $mainpagedetails[0]->ward }};
        odd-footer-name: html_pf{{ $mainpagedetails[0]->ward }};
        even-footer-name: html_pf{{ $mainpagedetails[0]->ward }};
    }

@page ho{{ $mainpagedetails[0]->ward }} {
        odd-header-name: html_pho{{ $mainpagedetails[0]->ward }};
        even-header-name: html_pho{{ $mainpagedetails[0]->ward }};
        odd-footer-name: html_pf{{ $mainpagedetails[0]->ward }};
        even-footer-name: html_pf{{ $mainpagedetails[0]->ward }};
    }

div.h{{ $mainpagedetails[0]->ward }} {
        page-break-before: left;
        page: h{{ $mainpagedetails[0]->ward }};
        resetpagenum: 1;
    }

div.ho{{ $mainpagedetails[0]->ward }} {
        page-break-before: right;
        page: ho{{ $mainpagedetails[0]->ward }};
    }   

@page h{{ $mainpagedetails[0]->ward }}:first {
	resetpagenum:1;
} 