<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include Breadcrumbs %>
        <hr>

        <h1>$Headline.RAW $Year</h1>
        <br>
        <% if $Lead %><p>$Lead.RAW</p><% end_if %>
        <% if $Content %>
            $Content
        <% end_if %>

        <% if $PaginatedOperations %>
            <% loop $PaginatedOperations %>
                <h3 id="operation-$ID">$Title.RAW</h3>
                <p><strong>Nr.</strong> $Number |
                    <strong>Datum</strong> $Date.Format('dd.MM.y') |
                    <strong>Beginn</strong> $Begin.Format('HH:mm') Uhr |
                    <strong>Ende</strong> $End.Format('HH:mm') Uhr
                    <% if $People %>
                        | <strong><i class="fas" title="$People Kameraden/innen">&#xf0c0;</i>&nbsp; $People</strong>
                    <% end_if %>
                </p>
                <p>$Content</p>
                <% if $OperationForces %>
                    <p><strong>Einsatzkräfte</strong></p>
                    <ul class="actions">
                        <% loop $OperationForces %>
                            <li class="margin-bottom">
                            <% if $URL %>
                                <a href="$URL" title="$Title" target="_blank" class="button alt small">$Title</a>
                            <% else %>
                                $Title
                            <% end_if %>
                            </li>
                        <% end_loop %>
                    </ul>
                <% end_if %>
                <% if $Links %>
                    <p><strong>Links</strong></p>
                    <ul class="actions">
                        <% loop $Links %>
                            <li class="margin-bottom">
                                <a href="$URL" title="$Source" target="_blank" class="button alt small">$Title</a>
                            </li>
                        <% end_loop %>
                    </ul>
                <% end_if %>
                <% if $OperationImages %>
                    <p><strong>Bilder</strong></p>
                    <div id="$ID">
                        <% loop $OperationImages %>
                            <a href="$Image.URL" alt="$Title" title="$Title">
                                <img src="$Image.URL"
                                     class="img-rounded image-list"
                                     alt="$Title"
                                     title="$Title">
                            </a>
                        <% end_loop %>
                    </div>
                <% end_if %>
                <br>
                <% if not $last %>
                    <hr>
                <% end_if %>
            <% end_loop %>

            <hr>

            <% if $PaginatedOperations.MoreThanOnePage %>
                <% if $PaginatedOperations.NotFirstPage %>
                    <a class="prev button alt small" href="$PaginatedOperations.PrevLink">Vorherige</a>
                <% end_if %>
                <% loop $PaginatedOperations.PaginationSummary %>
                    <% if $CurrentBool %>
                        <p class="button alt disabled">$PageNum</p>
                    <% else %>
                        <% if $Link %>
                            <a href="$Link" class="button alt small">$PageNum</a>
                        <% else %>
                            ...
                        <% end_if %>
                    <% end_if %>
                <% end_loop %>
                <% if $PaginatedOperations.NotLastPage %>
                    <a class="next button alt small" href="$PaginatedOperations.NextLink">Nächste</a>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</section>
