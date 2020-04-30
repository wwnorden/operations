<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include Breadcrumbs %>
        <hr>

        <% if $Headline %><h1>$Headline.RAW</h1><% end_if %>
        <br>
        <% if $Lead %><p>$Lead.RAW</p><% end_if %>
        <% if $Content %>
            $Content
        <% end_if %>

        <% if $PaginatedOperationsPerYear %>
            <div class="columns">
                <% loop $PaginatedOperationsPerYear %>
                    <div class="column operations-per-year">
                        <a href="$Top.URLSegment/$Year/">
                            <% if $Image %>
                                $Image.Image.Fill(400,300)
                            <% else %>
                                <img class="margin-top-7" title="FF Rathmannsdorf-Felmerholz"
                                     src="$ThemeDir/img/feuerwehr.jpg">
                            <% end_if %>
                        </a>
                        <div class="operations-per-year-infos">
                            <a href="einsaetze/$Year/">
                                Jahr $Year | Anzahl Einsätze $Operations
                            </a>
                        </div>
                    </div>
                <% end_loop %>
            </div>
        <% end_if %>
    </div>
</section>
