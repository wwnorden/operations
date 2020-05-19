<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include BreadCrumbs %>
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
                        <a href="$Top.Link$Year/">
                            <% if $Image %>
                                $Image.Image.Fill(400,300)
                            <% end_if %>
                        </a>
                        <div class="operations-per-year-infos">
                            <a href="$Top.Link$Year/">
                                <% _t('WWN\Operations\OperationPage.Year','Year') %> $Year | <% _t('WWN\Operations\OperationPage.CountOperations','Count operations') %> $Operations
                            </a>
                        </div>
                    </div>
                <% end_loop %>
            </div>
        <% end_if %>
    </div>
</section>
